<?php

namespace Aternos\BlobBuild\Test\Unit;

use Aternos\BlobBuild\Client\BlobBuildAPIClient;
use Aternos\BlobBuild\Model\Build as BuildModel;
use Aternos\BlobBuild\Model\Project as ProjectModel;
use Aternos\BlobBuild\Model\ProjectListItem as ProjectListItemModel;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class UnitTestCase extends TestCase
{
    protected MockHandler $httpMockHandler;
    protected BlobBuildAPIClient $client;
    protected array $requestHistory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpMockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->httpMockHandler);
        $this->requestHistory = [];
        $handlerStack->push(Middleware::history($this->requestHistory));
        $this->client = new BlobBuildAPIClient(
            httpClient: new Client(['handler' => $handlerStack]),
            userAgent: "aternos/php-blob-build-api@0.1.0 (contact@aternos.org)",
            apiToken: "KcehKsHohfxnyuHHW6LYwMFhDYQaD9CtjCtktnm2PdasPvenmBrYeQFcBwXagbZd",
        );
    }
    
    protected function getFixture(string $path): string
    {
        $data = file_get_contents(__DIR__ . "/Fixtures/" . $path);
        if ($data === false) {
            throw new RuntimeException("Could not read fixture file: " . $path);
        }
        return $data;
    }
    
    protected function addResponse(string $content, int $status = 200, array $headers = []): void
    {
        $this->httpMockHandler->append(new Response($status, $headers, $content));
    }

    protected function assertSoundMuffler(ProjectModel|ProjectListItemModel $rawProject): void
    {
        $this->assertEquals("SoundMuffler", $rawProject->getName());
        if ($rawProject instanceof ProjectListItemModel) {
            $this->assertEquals("J3fftw", $rawProject->getOwner());
        }
        $this->assertEquals("SoundMuffler is a Slimefun4 Addon that is created to stop the horrible noices from mob farms, piston machines and many other things that make sound. By using a SoundMuffler you are able to reduce the sound or even stop the sounds in an area.", $rawProject->getDescription());
        $this->assertEquals("https://github.com/Slimefun-Addon-Community/SoundMuffler", $rawProject->getRepoLink());
        $this->assertNull($rawProject->getWikiLink());
        $this->assertEquals(["Dev"], $rawProject->getReleaseChannels());

        $defaultChannel = $rawProject->getDefaultReleaseChannel();
        $this->assertEquals("Dev", $defaultChannel->getName());
        $this->assertEquals("1.20+", $defaultChannel->getSupportedVersions());
        $this->assertEquals(["Slimefun", "ProtocolLib"], $defaultChannel->getDependencies());
        $this->assertEquals('$project.jar', $defaultChannel->getFileNaming());
    }

    protected function assertSoundMufflerBuild(BuildModel $build): void
    {
        $this->assertEquals("SoundMuffler", $build->getProjectName());
        $this->assertEquals("Dev", $build->getReleaseChannel());
        $this->assertEquals(1, $build->getBuildId());
        $this->assertEquals("6228088d899da856a1542c725105aa86eac9c48cc8b97d0cf07972bc55276b45", $build->getChecksum());
    }
}