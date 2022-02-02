<?php

namespace App\Console\Commands;

use App\Models\Eloquent\FilterAutoranking;
use App\Services\Google\CloudStorageService;
use CreateFiltersAutorankingTable;
use Google\Cloud\Storage\StorageObject;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class FetchFiltersAutoranking extends Command
{
    public const BUCKET = 'rz_transfer';
    public const FILE_PREFIX = 'filters_autoranking';
    public const TEMP_FILE_DESTINATION = 'app/temp/autoranking/';
    public const EXPECTED_COUNT_OF_COLUMNS_IN_ROW = 11;
    public const ADMISSIBLE_PERCENTAGE_LIMIT_OF_DIFFERENCE = 30;
    public const TEMP_TABLE_SUFFIX = '_tmp';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoranking:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and save filters auto ranking from Google BigQuery';

    private CloudStorageService $cloudStorage;

    private FilterAutoranking $model;

    private FilesystemAdapter $disk;

    /**
     * Array with saved files paths
     *
     * @var array
     */
    private array $paths = [];

    /**
     * Temporary table name
     *
     * @var string
     */
    private string $tempTable;

    /**
     * @param CloudStorageService $cloudStorage
     * @param FilterAutoranking $model
     */
    public function __construct(CloudStorageService $cloudStorage, FilterAutoranking $model)
    {
        parent::__construct();

        require_once base_path('database/migrations/2022_02_02_103420_create_filters_autoranking_table.php');
        $this->cloudStorage = $cloudStorage;
        $this->model = $model;
        $this->disk = Storage::disk('local');
        $this->tempTable = $model->getTable() . self::TEMP_TABLE_SUFFIX;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Throwable
     */
    public function handle(): int
    {
        $this->clearTempDirectory();
        $files = $this->cloudStorage->getFilesFromBucket(self::BUCKET, self::FILE_PREFIX);

        foreach ($files as $item) {
            $this->paths[] = $this->saveFile($item);
        }

        if (!$this->checkFiles($this->paths)) {
            $this->error('Percentage of difference to big, so we can not proceed this job');

            return 0;
        }

        $this->createTempTable();

        foreach ($this->paths as $path) {
            $result = $this->processCsv($path);

            if (!$result) {
                $message = 'File [' . $path . '] cannot be processed completely';
                $this->error($message);
                Log::error($message);
                break;
            }

            $this->info('File [' . $path . '] processed completely');
        }

        $this->switchTables();

        return 0;
    }

    /**
     * Clear directory from redundant and old files
     *
     * @return void
     */
    private function clearTempDirectory(): void
    {
        $destination = Str::after(self::TEMP_FILE_DESTINATION, 'app/');

        $this->disk->deleteDirectory($destination);
        $this->disk->createDir($destination);
    }

    /**
     * Compare count of lines in files with count of lines in table
     * Returns true if files can be processed
     *
     * @param array $paths
     * @return bool
     */
    private function checkFiles(array $paths): bool
    {
        $totalFilesRowsCount = 0 - count($paths);

        foreach ($paths as $path) {
            // In case of the files are too big,
            // we can't read it at once and count the number of lines.
            // Therefore, we go through each line of it
            $file = fopen($path, 'rb');

            while (!feof($file)) {
                fgets($file);
                $totalFilesRowsCount++;
            }
        }

        $dbRowsCount = $this->model->query()->count();
        $dbRowsCount = $dbRowsCount === 0 ? 1 : $dbRowsCount; // preventing zero division
        $percentageOfDifference = ($dbRowsCount - $totalFilesRowsCount) / $dbRowsCount * 100;

        return $percentageOfDifference < self::ADMISSIBLE_PERCENTAGE_LIMIT_OF_DIFFERENCE;
    }

    /**
     * Create temporary table for data saving
     *
     * @return void
     */
    private function createTempTable(): void
    {
        $migration = new CreateFiltersAutorankingTable($this->tempTable);
        $migration->up();
    }

    /**
     * Drop table with old data and rename temp table
     *
     * @return void
     * @throws Throwable
     */
    private function switchTables(): void
    {
        DB::transaction(function () {
            $table = $this->model->getTable();
            Schema::dropIfExists($table);
            Schema::rename($this->tempTable, $table);
        });
    }

    /**
     * Save to file and returns destination path
     *
     * @param StorageObject $object
     * @return string
     */
    private function saveFile(StorageObject $object): string
    {
        $destination = storage_path(self::TEMP_FILE_DESTINATION . $object->name());
        $object->downloadToFile($destination)->close();

        return $destination;
    }

    /**
     * Process csv and save avery row to DB
     *
     * @param string $path
     * @return bool
     */
    private function processCsv(string $path): bool
    {
        try {
            $file = fopen($path, 'rb');
        } finally {
            if (!isset($file) || $file === false) {
                Log::error("Cannot open file [$path]");
                return false;
            }
        }

        $row = 1;
        while (($rawData = fgetcsv($file)) !== false) {
            if ($row === 1) {
                $row++;
                continue;
            }

            $countOfColumns = count($rawData);
            if ($countOfColumns !== self::EXPECTED_COUNT_OF_COLUMNS_IN_ROW) {
                Log::error("There are mismatch of columns in row $row", [
                    'filePath' => $path,
                    'countOfColumns' => $countOfColumns,
                    'expectedCountOfColumns' => self::EXPECTED_COUNT_OF_COLUMNS_IN_ROW,
                    'data' => $rawData,
                ]);

                fclose($file);
                return false;
            }

            try {
                $data = $this->parseData($rawData);
                $this->model
                    ->setTable($this->tempTable)
                    ->create($data);
            } catch (Throwable $e) {
                Log::error($e->getMessage());
                fclose($file);
                return false;
            }

            $row++;
        }

        return fclose($file);
    }

    /**
     * Map raw data to savable array
     *
     * @param array $raw
     * @return array
     */
    private function parseData(array $raw): array
    {
        return [
            'parent_id' => $raw[0],
            'filter_name' => $raw[1],
            'filter_value' => $raw[2],
            'filter_rank' => $raw[3],
            'is_value_show' => ($raw[4] === true || $raw[4] === 'true') ? 1 : 0,
            'is_filter_show' => ($raw[5] === true || $raw[5] === 'true') ? 1 : 0,
        ];
    }
}
