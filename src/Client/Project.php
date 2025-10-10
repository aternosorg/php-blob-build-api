<?php

namespace Aternos\BlobBuild\Client;

use Aternos\BlobBuild\Model\Project as ProjectModel;
use Aternos\BlobBuild\Model\ProjectDefaultReleaseChannel;

class Project extends BaseProject
{
    public function __construct(
        protected ProjectModel $data,
        BlobBuildAPIClient     $client
    )
    {
        parent::__construct($client);
    }

    /**
     * Get the project object as returned by the API
     * @return ProjectModel
     */
    public function getData(): ProjectModel
    {
        return $this->data;
    }

    protected function getName(): string
    {
        return $this->data->getName();
    }

    protected function getDefaultReleaseChannel(): ProjectDefaultReleaseChannel
    {
        return $this->data->getDefaultReleaseChannel();
    }
}