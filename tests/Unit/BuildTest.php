<?php

namespace Aternos\BlobBuild\Test\Unit;

use Aternos\BlobBuild\Client\Build;

class BuildTest extends UnitTestCase
{
    public function testGetProject(): void
    {
        $build = $this->getBuild();
        $this->assertSoundMufflerBuild($build->getData());
        
        $this->addResponse($this->getFixture("projects/SoundMuffler.json"));
        $project = $build->getProject();
        $this->assertSoundMuffler($project->getData());
    }
    
    protected function getBuild(): Build
    {
        $this->addResponse($this->getFixture("builds/SoundMuffler/latest.json"));
        return $this->client->getLatestProjectBuildInChannel("SoundMuffler", "Dev");
    }
}