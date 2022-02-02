<?php

namespace Tests\Unit\Services\Google;

use App\Services\Google\CloudStorageService;
use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\ObjectIterator;
use Google\Cloud\Storage\StorageClient;
use Mockery\MockInterface;
use Tests\TestCase;

class CloudStorageServiceTest extends TestCase
{
    private CloudStorageService $service;

    /**
     * @inheritDoc
     * @noinspection PhpParamsInspection
     */
    protected function setUp(): void
    {
        parent::setUp();

        $storageClient = $this->mock(StorageClient::class, function (MockInterface $mock) {
            $bucket = $this->mock(Bucket::class, function (MockInterface $mock) {
                $mock->shouldReceive('exists')->andReturn(true);

                $iterator = $this->mock(ObjectIterator::class);
                $mock->shouldReceive('objects')->andReturn($iterator);
            });

            $mock->shouldReceive('bucket')->andReturn($bucket);
        });
        $this->service = new CloudStorageService($storageClient);
    }

    public function testItWillReturnBaseClient(): void
    {
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(StorageClient::class, $this->service->getBaseClient());
    }

    public function testItWillReturnExistingBucket(): void
    {
        $bucket = $this->service->getBucket('rz_transfer');
        $this->assertTrue($bucket->exists());
    }

    public function testItWillReturnFilesFromBucketWithPrefix(): void
    {
        $files = $this->service->getFilesFromBucket('rz_transfer', 'filters_autoranking');

        $this->assertInstanceOf(ObjectIterator::class, $files);
    }

    public function testItWillReturnFilesFromBucketWithoutPrefix(): void
    {
        $files = $this->service->getFilesFromBucket('rz_transfer');

        $this->assertInstanceOf(ObjectIterator::class, $files);
    }
}
