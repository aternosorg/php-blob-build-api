<?php

namespace Aternos\BlobBuild\Client;

use Aternos\BlobBuild\ApiException;
use Aternos\BlobBuild\Model\ProjectDefaultReleaseChannel;

abstract class BaseProject
{
    public function __construct(
        protected BlobBuildAPIClient $client,
    )
    {
    }
    
    protected abstract function getName(): string;
    
    protected abstract function getDefaultReleaseChannel(): ProjectDefaultReleaseChannel;

    /**
     * Get the URL of the project page on blob.build
     * @return string
     */
    public function getPageUrl(): string
    {
        return "https://blob.build/project/" . $this->getName();
    }

    /**
     * Get all builds of this project
     * @return array<string, Build[]> Map of release channels to builds in that channel
     * @throws ApiException
     */
    public function getBuilds(): array
    {
        return $this->client->getProjectBuilds($this->getName());
    }

    /**
     * Get the latest build of this project in a specific channel
     * @param string $channelName
     * @return Build
     * @throws ApiException
     */
    public function getLatestBuildInChannel(string $channelName): Build
    {
        return $this->client->getLatestProjectBuildInChannel($this->getName(), $channelName);
    }

    /**
     * Get the latest build of this project in its default channel
     * @return Build
     * @throws ApiException
     */
    public function getLatestBuildInDefaultChannel(): Build
    {
        return $this->getLatestBuildInChannel($this->getDefaultReleaseChannel()->getName());
    }

    /**
     * Get a specific build of this project in a specific channel by its build number
     * @param string $channel
     * @param int $buildNumber
     * @return Build
     * @throws ApiException
     */
    public function getBuild(string $channel, int $buildNumber): Build
    {
        return $this->client->getProjectBuild($this->getName(), $channel, $buildNumber);
    }
}