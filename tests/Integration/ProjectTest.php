<?php

namespace Aternos\BlobBuild\Test\Integration;

class ProjectTest extends IntegrationTestCase
{
    public function testSearch(): void
    {
        $projects = $this->client->searchProjects("sound");
        $this->assertNotEmpty($projects);
        $this->assertCount(1, $projects);

        $build = $projects[0]->getLatestBuildInDefaultChannel();
        $this->assertEquals("SoundMuffler", $build->getData()->getProjectName());
    }
    
    public function testGetProject(): void
    {
        $project = $this->client->getProject("SoundMuffler");
        $this->assertEquals("SoundMuffler", $project->getData()->getName());
        $this->assertEquals("https://blob.build/project/SoundMuffler", $project->getPageUrl());
    }
    
    public function testGetBuilds(): void
    {
        $project = $this->client->getProject("SoundMuffler");
        $builds = $project->getBuilds();
        $this->assertArrayHasKey("Dev", $builds);
        $this->assertNotEmpty($builds["Dev"]);
    }
    
    public function testGetLatestBuildInChannel(): void
    {
        $project = $this->client->getProject("SoundMuffler");
        $build = $project->getLatestBuildInChannel("Dev");
        $this->assertEquals("SoundMuffler", $build->getData()->getProjectName());
    }
    
    public function testGetLatestBuildInDefaultChannel(): void
    {
        $project = $this->client->getProject("SoundMuffler");
        $build = $project->getLatestBuildInDefaultChannel();
        $this->assertEquals("SoundMuffler", $build->getData()->getProjectName());
    }
    
    public function testGetBuild(): void
    {
        $project = $this->client->getProject("SoundMuffler");
        $build = $project->getBuild("Dev", 1);
        $this->assertEquals(1, $build->getData()->getBuildId());
    }
}