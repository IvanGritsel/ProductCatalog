<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Aws\Exception\AwsException;
use App\Util\CsvConverter;
use Aws\Result;
use Aws\S3\S3Client;
use Aws\Ses\SesClient;
use Exception;

class AwsService
{
    private ProductRepository $productRepository;
    private S3Client $s3Client;
    private SesClient $sesClient;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
            'use_path_style_endpoint' => true,
            'endpoint' => 'http://localhost:4566',
            'credentials' => [
                'key' => '1234', //Not actual credentials since localstack is used
                'secret' => '1234',
            ],
        ]);
        $this->sesClient = new SesClient([
            'version' => 'latest',
            'region' => 'us-east-1',
            'use_path_style_endpoint' => true,
            'endpoint' => 'http://localhost:4566',
            'credentials' => [
                'key' => '1234', //Not actual credentials since localstack is used
                'secret' => '1234',
            ],
        ]);
        $this->s3Client->createBucket([
            'Bucket' => 'servicebucket',
        ]);
    }

    public function exportCatalog(): bool|string
    {
        try {
            $body = CsvConverter::convert($this->productRepository->findAll());
            $result = $this->s3Client->putObject([
                'Bucket' => 'servicebucket',
                'Key' => date('YmdHis'),
                'Body' => $body,
            ]);
            $resultUrl = $result->toArray();
            $resultUrl = $resultUrl['ObjectURL'];
            $this->sendAdminEmail($resultUrl);
            return $resultUrl;
        } catch (AwsException $e) {
            throw new Exception('Cannot export catalog', 400, $e);
        }
    }

    public function sendAdminEmail(string $resultUrl): void
    {
        try {
            $result = $this->sesClient->sendEmail([
                'Destination' => [
                    'ToAddresses' => ['ivan.gritsel@immowise-group.com'],
                ],
                'ReplyToAddresses' => ['admin@awesomeshop.com'],
                'Source' => 'admin@awesomeshop.com',
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'UTF-8',
                            'Data' => "<h1>Service Email</h1><p>Catalog was saved in S3 and is accessible through <a href='$resultUrl'>this</a> link</p>",
                        ],
                        'Text' => [
                            'Charset' => 'UTF-8',
                            'Data' => 'This message was sent automatically. Do not respond',
                        ],
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => 'Catalog saved (test)',
                    ],
                ],
            ]);
        } catch (AwsException $e) {
            throw new AwsException('Cant send email', $e->getCode(), [], $e);
        }
    }
}