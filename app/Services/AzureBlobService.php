<?php

namespace App\Services;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\DeleteBlobOptions;

class AzureBlobService
{
    protected $blobClient;
    protected $accountName;
    protected $accountKey;
    protected $container;

    public function __construct()
    {
        $this->accountName = env('AZURE_STORAGE_ACCOUNT_NAME');
        $this->accountKey = env('AZURE_STORAGE_ACCOUNT_KEY');
        $this->container   = env('AZURE_STORAGE_CONTAINER_NAME');

        $connectionString = "DefaultEndpointsProtocol=https;AccountName={$this->accountName};AccountKey={$this->accountKey};EndpointSuffix=core.windows.net";
        $this->blobClient = BlobRestProxy::createBlobService($connectionString);
    }

    public function listFilesWithSas()
    {
        $options = new ListBlobsOptions();
        $blobList = $this->blobClient->listBlobs($this->container, $options);
        $blobs = $blobList->getBlobs();

        $files = [];

        foreach ($blobs as $blob) {
            $blobName = $blob->getName();
            $url = env('AZURE_STORAGE_BLOB_URL') . "/{$this->container}/{$blobName}";

            $files[] = [
                'name' => $blobName,
                'url'  => $url
            ];
        }

        return $files;
    }

    public function uploadFile($file)
    {
        $blobName = $file->getClientOriginalName();
        $content = fopen($file->getRealPath(), "r");

        $this->blobClient->createBlockBlob(
            $this->container,
            $blobName,
            $content
        );
    }

    public function deleteFile($blobName)
    {
        $this->blobClient->deleteBlob($this->container, $blobName, new DeleteBlobOptions());
    }
}
