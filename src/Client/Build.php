<?php

namespace Aternos\BlobBuild\Client;

use Aternos\BlobBuild\ApiException;
use Aternos\BlobBuild\Model\Build as BuildModel;

class Build
{
    public function __construct(
        protected BuildModel         $data,
        protected BlobBuildAPIClient $client,
    )
    {
    }

    /**
     * Get the build object as returned by the API
     * @return BuildModel
     */
    public function getData(): BuildModel
    {
        return $this->data;
    }

    /**
     * Get the project this build belongs to
     * @return Project
     * @throws ApiException
     */
    public function getProject(): Project
    {
        return $this->client->getProject($this->data->getProjectName());
    }
}