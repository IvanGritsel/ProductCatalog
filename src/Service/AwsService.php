<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Aws\Exception\AwsException;
use App\Util\CsvConverter;
use Aws\Result;
use Aws\S3\S3Client;
use Exception;

class AwsService
{
    private ProductRepository $productRepository;
    private S3Client $client;
    private Result $bucket;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->client = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
            'use_path_style_endpoint' => true,
            'endpoint' => 'http://localhost:4566',
            'credentials' => [
                'key' => '1234', //Not actual credentials since localstack is used
                'secret' => '1234',
            ],
        ]);
        $this->bucket = $this->client->createBucket([
            'Bucket' => 'servicebucket',
        ]);
    }

    public function exportCatalog(): bool|string
    {
        try {
            $body = CsvConverter::convert($this->productRepository->findAll());
            $result = $this->client->putObject([
                'Bucket' => 'servicebucket',
                'Key' => date('YmdHis'),
                'Body' => $body,
            ]);
            $resultArray = $result->toArray();
            return $resultArray['ObjectURL'];
        } catch (AwsException $e) {
            throw new Exception('Cannot export catalog', 500, $e);
        }
    }
}