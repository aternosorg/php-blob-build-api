<?php

namespace Aternos\BlobBuild\Client;

use Aternos\BlobBuild\Model\ProjectDefaultReleaseChannel;
use Aternos\BlobBuild\Model\ProjectListItem as ProjectListItemModel;

class ProjectListItem extends BaseProject
{
    public function __construct(
        protected ProjectListItemModel $data,
        BlobBuildAPIClient             $client
    )
    {
        parent::__construct($client);
    }
    
    /**
     * Get the project object as returned by the API
     * @return ProjectListItemModel
     */
    public function getData(): ProjectListItemModel
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