<?php

namespace Aternos\BlobBuild\Client;

use Aternos\BlobBuild\Api\BuildsApi;
use Aternos\BlobBuild\Api\ProjectsApi;
use Aternos\BlobBuild\Api\UsersApi;
use Aternos\BlobBuild\ApiException;
use Aternos\BlobBuild\Configuration;
use Aternos\BlobBuild\Model\Build as BuildModel;
use Aternos\BlobBuild\Model\ProjectListItem as ProjectListItemModel;
use Aternos\BlobBuild\Model\SearchRequest;
use GuzzleHttp\ClientInterface;

class BlobBuildAPIClient
{
    protected Configuration $configuration;
    protected ?ClientInterface $httpClient;
    protected UsersApi $users;
    protected ProjectsApi $projects;
    protected BuildsApi $builds;

    public function __construct(
        ?Configuration   $configuration = null,
        ?ClientInterface $httpClient = null,
        ?string          $userAgent = null,
        ?string          $apiToken = null,
    )
    {
        $this->httpClient = $httpClient;
        if ($configuration === null) {
            $configuration = new Configuration();
            $configuration->setUserAgent($userAgent ?? "php-blob-build-api/1.0.1");
            if ($apiToken !== null) {
                $configuration->setAccessToken($apiToken);
            }
        }
        $this->setConfiguration($configuration);
    }

    /**
     * Set the configuration used for all HTTP requests
     *
     * @param Configuration $configuration
     * @return $this
     */
    public function setConfiguration(Configuration $configuration): static
    {
        $this->configuration = $configuration;
        $this->users = new UsersApi($this->httpClient, $this->configuration);
        $this->projects = new ProjectsApi($this->httpClient, $this->configuration);
        $this->builds = new BuildsApi($this->httpClient, $this->configuration);
        return $this;
    }

    /**
     * Set the user agent used for all HTTP requests
     *
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent(string $userAgent): static
    {
        $this->configuration->setUserAgent($userAgent);
        return $this->setConfiguration($this->configuration);
    }

    /**
     * Set the api token used for all HTTP requests
     *
     * @param string $token
     * @return $this
     */
    public function setApiToken(string $token): static
    {
        $this->configuration->setAccessToken($token);
        return $this->setConfiguration($this->configuration);
    }

    /**
     * Set the HTTP client used for all requests.
     * When null, the default {@link \GuzzleHttp\Client} will be used.
     *
     * @param ClientInterface|null $httpClient
     * @return $this
     */
    public function setHttpClient(?ClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this->setConfiguration($this->configuration);
    }

    /**
     * Get the authenticated user
     * @return User
     * @throws ApiException
     */
    public function getAuthenticatedUser(): User
    {
        return new User(
            $this->users->getAuthenticatedUser()->getData(),
            $this,
        );
    }
    
    /**
     * Search for projects by name
     * @param string $query The search query (max length: 100 characters)
     * @return ProjectListItem[]
     * @throws ApiException
     */
    public function searchProjects(string $query): array
    {
        $request = (new SearchRequest())->setQuery($query);
        return array_map($this->mapProjectListItem(...), $this->projects->searchProjects($request)->getData());
    }
    
    /**
     * List all projects
     * @return ProjectListItem[]
     * @throws ApiException
     */
    public function listProjects(): array
    {
        return array_map($this->mapProjectListItem(...), $this->projects->listProjects()->getData());
    }
    
    /**
     * Get a project by its name
     * @param string $name
     * @return Project
     * @throws ApiException
     */
    public function getProject(string $name): Project
    {
        return new Project($this->projects->getProject($name)->getData(), $this);
    }
    
    /**
     * Get all builds of a project by its name
     * @param string $projectName
     * @return array<string, Build[]> Map of release channels to builds in that channel
     * @throws ApiException
     */
    public function getProjectBuilds(string $projectName): array
    {
        return array_map(
            fn(array $builds) => array_map($this->mapBuild(...), $builds),
            $this->builds->getProjectBuilds($projectName)->getData()
        );
    }

    /**
     * Get the latest build of a project in a specific channel
     * @param string $projectName
     * @param string $channel
     * @return Build
     * @throws ApiException
     */
    public function getLatestProjectBuildInChannel(string $projectName, string $channel): Build
    {
        return $this->mapBuild($this->builds->getLatestProjectBuildInChannel($projectName, $channel)->getData());
    }

    /**
     * Get a specific build of a project in a specific channel by its build number
     * @param string $projectName
     * @param string $channel
     * @param int $buildNumber
     * @return Build
     * @throws ApiException
     */
    public function getProjectBuild(string $projectName, string $channel, int $buildNumber): Build
    {
        return $this->mapBuild($this->builds->getProjectBuild($projectName, $channel, $buildNumber)->getData());
    }
    
    /**
     * Map a ProjectModel to a Project
     * @param ProjectListItemModel $model
     * @return ProjectListItem
     */
    protected function mapProjectListItem(ProjectListItemModel $model): ProjectListItem
    {
        return new ProjectListItem($model, $this);
    }
    
    /**
     * Map a BuildModel to a Build
     * @param BuildModel $model
     * @return Build
     */
    protected function mapBuild(BuildModel $model): Build
    {
        return new Build($model, $this);
    }
}