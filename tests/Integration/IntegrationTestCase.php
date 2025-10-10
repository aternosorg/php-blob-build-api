<?php

namespace Aternos\BlobBuild\Test\Integration;

use Aternos\BlobBuild\Client\BlobBuildAPIClient;
use PHPUnit\Framework\TestCase;

class IntegrationTestCase extends TestCase
{
    protected ?string $apiToken;
    protected BlobBuildAPIClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new BlobBuildAPIClient();
        $this->client->setUserAgent("aternos/php-blob-build-api@0.1.0 (contact@aternos.org)");
        $this->apiToken = getenv("BLOB_BUILD_API_TOKEN") ?: null;
        if ($this->apiToken !== null) {
            $this->client->setApiToken($this->apiToken);
        }
    }
}