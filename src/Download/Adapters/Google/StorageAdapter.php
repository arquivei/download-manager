<?php

namespace Arquivei\DownloadManager\Download\Adapters\Google;

class StorageAdapter
{
    private $client;
    private $bucket;

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

    public function setBucket(string $bucket)
    {
        $this->bucket = $this->client->bucket('arquivei-dev-team');
        return $this;
    }

    public function signUrl(string $storagePath, int $expireSeconds = 45)
    {
        $object = $this->bucket->object($storagePath);
        return $object->signedUrl(
            new \DateTime(sprintf('%d seconds', $expireSeconds)),
            [
                'version' => 'v4',
            ]
        );
    }
}