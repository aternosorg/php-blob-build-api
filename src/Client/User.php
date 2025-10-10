<?php

namespace Aternos\BlobBuild\Client;

use Aternos\BlobBuild\ApiException;
use Aternos\BlobBuild\Model\User as UserModel;

class User
{
    public function __construct(
        protected UserModel          $data,
        protected BlobBuildAPIClient $client,
    )
    {
    }
    
    /**
     * Get the user object as returned by the API
     * @return UserModel
     */
    public function getData(): UserModel
    {
        return $this->data;
    }

    /**
     * Get all projects owned by this user
     * @return ProjectListItem[]
     * @throws ApiException
     */
    public function getProjects(): array
    {
        return array_filter($this->client->listProjects(), function (ProjectListItem $project): bool {
            return $project->getData()->getOwner() === $this->data->getName();
        });
    }
}