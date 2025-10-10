<?php

namespace Aternos\BlobBuild\Test\Unit;

use Aternos\BlobBuild\Client\Build;
use Aternos\BlobBuild\Client\Project;
use Aternos\BlobBuild\Client\ProjectListItem;
use Aternos\BlobBuild\Model\Build as BuildModel;
use Aternos\BlobBuild\Model\Project as ProjectModel;
use Aternos\BlobBuild\Model\ProjectListItem as ProjectListItemModel;

class ProjectTest extends UnitTestCase
{
    public function testSearch(): void
    {
        $this->addResponse($this->getFixture("projects/list/sound.json"));
        $projects = $this->client->searchProjects("sound");
        $this->assertNotEmpty($projects);
        $this->assertCount(1, $projects);
        $this->assertInstanceOf(ProjectListItem::class, $projects[0]);
        
        $project = $projects[0];
        $this->assertSoundMuffler($project->getData());
        $this->assertEquals("https://blob.build/project/SoundMuffler", $project->getPageUrl());
        
        $this->addResponse($this->getFixture("builds/SoundMuffler/latest.json"));
        $build = $project->getLatestBuildInDefaultChannel();
        $this->assertSoundMufflerBuild($build->getData());
    }
    
    public function testGetProject(): void
    {
        $this->assertSoundMuffler($this->getProject()->getData());
    }
    
    public function testGetPageUrl(): void
    {
        $project = $this->getProject();
        $this->assertEquals("https://blob.build/project/SoundMuffler", $project->getPageUrl());
    }
    
    public function testGetBuilds(): void
    {
        $project = $this->getProject();

        $this->addResponse($this->getFixture("builds/SoundMuffler/all.json"));
        $builds = $project->getBuilds();
        $this->assertArrayHasKey("Dev", $builds);
        $this->assertCount(1, $builds["Dev"]);
        $build = $builds["Dev"][0];
        $this->assertInstanceOf(Build::class, $build);
        $this->assertSoundMufflerBuild($build->getData());
    }
    
    public function testGetLatestBuildInChannel(): void
    {
        $project = $this->getProject();

        $this->addResponse($this->getFixture("builds/SoundMuffler/latest.json"));
        $build = $project->getLatestBuildInChannel("Dev");
        $this->assertSoundMufflerBuild($build->getData());
    }

    public function testGetLatestBuildInDefaultChannel(): void
    {
        $project = $this->getProject();

        $this->addResponse($this->getFixture("builds/SoundMuffler/latest.json"));
        $build = $project->getLatestBuildInDefaultChannel();
        $this->assertSoundMufflerBuild($build->getData());
    }
    
    public function testGetBuild(): void
    {
        $project = $this->getProject();

        $this->addResponse($this->getFixture("builds/SoundMuffler/latest.json"));
        $build = $project->getBuild("Dev", 1);
        $this->assertSoundMufflerBuild($build->getData());
    }

    protected function getProject(): Project
    {
        $this->addResponse($this->getFixture("projects/SoundMuffler.json"));
        return $this->client->getProject("SoundMuffler");
    }
}