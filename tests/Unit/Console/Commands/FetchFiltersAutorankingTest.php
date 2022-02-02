<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\FetchFiltersAutoranking;
use App\Models\Eloquent\FilterAutoranking;
use App\Services\Google\CloudStorageService;
use ErrorException;
use Google\Cloud\Storage\ObjectIterator;
use Google\Cloud\Storage\StorageObject;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Psr\Http\Message\StreamInterface;
use ReflectionException;
use ReflectionMethod;
use Tests\TestCase;

class FetchFiltersAutorankingTest extends TestCase
{
    private FetchFiltersAutoranking $command;

    /**
     * @inheritDoc
     * @noinspection PhpParamsInspection
     */
    protected function setUp(): void
    {
        parent::setUp();

        $cloudStorage = $this->mock(CloudStorageService::class, function (MockInterface $mock) {
            $objectIterator = $this->partialMock(ObjectIterator::class, function (MockInterface $mock) {
                $mock->shouldReceive('current')->andReturn($this->mockFile());
                $mock->shouldReceive('rewind');
                $mock->shouldReceive('valid')->andReturn(true);
            });

            $mock->shouldReceive('getFilesFromBucket')->andReturn($objectIterator);
        });

        $model = $this->partialMock(FilterAutoranking::class, function (MockInterface $mock) {
            $mock->shouldReceive('query')->andReturnSelf();
            $mock->shouldReceive('count')->andReturn(5);
            $mock->shouldReceive('create');
        });

        $this->command = new FetchFiltersAutoranking($cloudStorage, $model);
    }

    /**
     * @param string $method
     * @param ...$args
     * @return mixed
     * @throws ReflectionException
     */
    private function invoke(string $method, ...$args)
    {
        $reflection = new ReflectionMethod($this->command, $method);
        $reflection->setAccessible(true);

        return $reflection->invoke($this->command, ...$args);
    }

    /**
     * @return MockInterface
     */
    private function mockFile(): MockInterface
    {
        return $this->mock(StorageObject::class, function (MockInterface $mock) {
            $stream = $this->mock(StreamInterface::class, function (MockInterface $mock) {
                $mock->shouldReceive('close');
            });
            $mock->shouldReceive('name')->andReturn('test.csv');
            $mock->shouldReceive('downloadToFile')->andReturn($stream);
        });
    }

    /**
     * @return string
     */
    private function saveValidFile(): string
    {
        $path = Str::after($this->command::TEMP_FILE_DESTINATION, 'app/') . 'test.csv';
        $content = <<<CONTENT
parent_id,filter_name,filter_value,filter_rank,is_value_show,is_filter_show,sessions,transactions,value_ratio,category_ratio,CR
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
CONTENT;
        $disk = Storage::disk('local');
        $disk->put($path, $content);

        return $path;
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanClearTempDirectory(): void
    {
        $path = Str::after($this->command::TEMP_FILE_DESTINATION, 'app/');

        $disk = Storage::disk('local');
        $disk->createDir($path);
        $disk->put($path . 'temp.csv', 'a,b');

        $this->invoke('clearTempDirectory');

        $this->assertEmpty($disk->files($this->command::TEMP_FILE_DESTINATION, true));
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanSaveFile(): void
    {
        $file = $this->mockFile();

        $this->assertEquals(
            storage_path($this->command::TEMP_FILE_DESTINATION . 'test.csv'),
            $this->invoke('saveFile', $file)
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testParseDataWillThrowErrorOnIncorrectData(): void
    {
        $this->expectException(ErrorException::class);
        $this->invoke('parseData', []);
    }

    /**
     * @throws ReflectionException
     */
    public function testParseDataWillReturnCorrectData(): void
    {
        $data = [
            4626923,
            'producer',
            'wild-turkey',
            2,
            "false",
            "true",
            1,
            0,
            0.00048828125,
            0.00015318627450980392,
            0
        ];

        $result = [
            'parent_id' => 4626923,
            'filter_name' => 'producer',
            'filter_value' => 'wild-turkey',
            'filter_rank' => 2,
            'is_value_show' => 0,
            'is_filter_show' => 1,
        ];

        $this->assertEquals($result, $this->invoke('parseData', $data));
    }

    /**
     * @throws ReflectionException
     */
    public function testProcessCsvWillReturnFalseIfFileNotFound(): void
    {
        $this->assertFalse($this->invoke('processCsv', 'path/to/not/found/file.csv'));
    }

    /**
     * @throws ReflectionException
     */
    public function testProcessCsvWillReturnFalseIfExpectedCountOfColumnsDoesntMatch(): void
    {
        $path = Str::after($this->command::TEMP_FILE_DESTINATION, 'app/') . 'test.csv';
        $content = <<<CONTENT
parent_id,filter_name,filter_value,filter_rank,is_value_show,is_filter_show,sessions,transactions,value_ratio,category_ratio,CR
4626923,producer,wild-turkey,2,false,true
CONTENT;
        $disk = Storage::disk('local');
        $disk->put($path, $content);

        $storagePath = storage_path('app/' . $path);
        $this->assertFalse($this->invoke('processCsv', $storagePath));

        $disk->delete($path);
    }

    /**
     * @throws ReflectionException
     */
    public function testProcessCsvWillReturnTrueOnSuccess(): void
    {
        $path = $this->saveValidFile();

        $storagePath = storage_path('app/' . $path);
        $this->assertTrue($this->invoke('processCsv', $storagePath));

        Storage::disk('local')->delete($path);
    }

    /**
     * @throws ReflectionException
     */
    public function testCheckFilesWillReturnTrueIfDifferencePercentageAcceptable(): void
    {
        $path = Str::after($this->command::TEMP_FILE_DESTINATION, 'app/') . 'test.csv';
        $content = <<<CONTENT
parent_id,filter_name,filter_value,filter_rank,is_value_show,is_filter_show,sessions,transactions,value_ratio,category_ratio,CR
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
4626923,producer,wild-turkey,2,false,true,1,0,0.00048828125,0.00015318627450980392,0
CONTENT;
        $disk = Storage::disk('local');
        $disk->put($path, $content);

        $paths[] = storage_path('app/' . $path);

        $this->assertTrue($this->invoke('checkFiles', $paths));
    }

    /**
     * @throws ReflectionException
     */
    public function testCheckFilesWillReturnFalseIfDifferencePercentageNotAcceptable(): void
    {
        $paths[] = storage_path('app/' . $this->saveValidFile());

        $this->assertFalse($this->invoke('checkFiles', $paths));
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanCreateTempTable(): void
    {
        $this->invoke('createTempTable');
        $table = FilterAutoranking::make()->getTable() . $this->command::TEMP_TABLE_SUFFIX;
        $this->assertTrue(Schema::hasTable($table));
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanSwitchTables(): void
    {
        $this->invoke('createTempTable');
        $this->invoke('switchTables');

        $table = FilterAutoranking::make()->getTable();
        $tempTable = $table . $this->command::TEMP_TABLE_SUFFIX;

        $this->assertTrue(Schema::hasTable($table));
        $this->assertFalse(Schema::hasTable($tempTable));
    }
}
