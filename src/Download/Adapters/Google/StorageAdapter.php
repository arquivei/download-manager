<?php

namespace Arquivei\DownloadManager\Download\Adapters\Google;

class StorageAdapter
{
    private $client;
    private $bucket;
    private $basePath;

    public function __construct()
    {
        $this->client =  new \Google\Cloud\Storage\StorageClient(
            [
                'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
                'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE')
            ]
        );

        return $this;
    }

    public function setBucket(string $bucket): StorageAdapter
    {
        $this->bucket = $this->client->bucket($bucket);
        return $this;
    }

    public function setBasePath(string $basePath): StorageAdapter
    {
        $this->basePath = $basePath;
        return $this;
    }

    protected function key(string $key): string
    {
        if (!is_null($this->basePath) && !empty($this->basePath) && strlen($this->basePath) > 0) {
            return sprintf('%s/%s', $this->basePath, $key);
        }
        return $key;
    }

    public function signUrl(string $storagePath, int $expireSeconds = 45): string
    {
        $object = $this->bucket->object($this->key($storagePath));
        return $object->signedUrl(
            new \DateTime(sprintf('%d seconds', $expireSeconds)),
            [
                'version' => 'v4',
            ]
        );
    }
}