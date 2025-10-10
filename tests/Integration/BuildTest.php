<?php

namespace Aternos\BlobBuild\Test\Integration;

class BuildTest extends IntegrationTestCase
{
    public function testGetProject(): void
    {
        $build = $this->client->getLatestProjectBuildInChannel("SoundMuffler", "Dev");
        $this->assertEquals("SoundMuffler", $build->getData()->getProjectName());
        
        $project = $build->getProject();
        $this->assertEquals("SoundMuffler", $project->getData()->getName());
    }
}