<?php

namespace Arquivei\DownloadManager\Download;

use Arquivei\DownloadManager\Download\Adapters\Google\StorageAdapter;
use Arquivei\DownloadManager\Download\Adapters\Monolog\LoggerAdapter;

class Downloader
{
    private $logger;
    private $client;

    public function __construct()
    {
        $this->logger = new LoggerAdapter();
        $this->client = new StorageAdapter();
    }

    public function download(string $bucket, string $basePath, string $storagePath)
    {
        try {
            return response()->redirectTo($this->signObject($bucket, $basePath, $storagePath));

        } catch (\Throwable | \Exception $exception) {
            $this->logger->error(
                '[DownloadManager/Download::Downloader] Generic error occurred',
                [
                    'exception' => get_class($exception),
                    'error' => $exception->getMessage(),
                    'data' => [
                        'bucket' => $bucket,
                        'base_path' => $basePath,
                        'storage_path' => $storagePath
                    ]
                ]
            );

            return response()->json(
                [
                    'status' => [
                        'code' => '500',
                        'message' => 'Internal Server Error',
                    ],
                    'error' => 'Generic error occurred'
                ],
                500
            );
        }
    }

    public function signObject(string $bucket, string $basePath, string $storagePath): string
    {
        return $this->client
            ->setBucket($bucket)
            ->signUrl($storagePath);
    }
}