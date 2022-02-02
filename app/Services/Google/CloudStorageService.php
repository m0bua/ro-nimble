<?php

namespace App\Services\Google;

use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\ObjectIterator;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\StorageObject;

class CloudStorageService
{
    /**
     * Base Google Cloud Storage client
     *
     * @var StorageClient
     */
    private StorageClient $client;

    public function __construct(StorageClient $client)
    {
        $this->client = $client;
    }

    /**
     * Return base client instance
     *
     * @return StorageClient
     */
    public function getBaseClient(): StorageClient
    {
        return $this->client;
    }

    /**
     * Get bucket (directory) with provided name
     *
     * @param string $bucket
     * @return Bucket
     */
    public function getBucket(string $bucket): Bucket
    {
        return $this->client->bucket($bucket);
    }

    /**
     * Get objects (files) from bucket with provided prefix if you need
     *
     * @param string $bucket
     * @param string|null $filePrefix
     * @return ObjectIterator|StorageObject[]
     */
    public function getFilesFromBucket(string $bucket, string $filePrefix = null): ObjectIterator
    {
        $options = [
            'fields' => 'items/name,nextPageToken',
        ];

        if ($filePrefix) {
            $options['prefix'] = $filePrefix;
        }

        return $this->getBucket($bucket)->objects($options);
    }
}
