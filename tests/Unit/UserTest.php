<?php

namespace Aternos\BlobBuild\Test\Unit;

use Aternos\BlobBuild\Client\Project;

class UserTest extends UnitTestCase
{
    public function testGetAuthenticatedUser(): void
    {
        $this->addResponse($this->getFixture("user/J3fftw.json"));
        $user = $this->client->getAuthenticatedUser();
        $this->assertEquals("J3fftw", $user->getData()->getName());
        $this->assertEquals("github", $user->getData()->getOauthProvider());
        $this->assertEquals("8468451", $user->getData()->getOauthId());
        $this->assertEquals("KcehKsHohfxnyuHHW6LYwMFhDYQaD9CtjCtktnm2PdasPvenmBrYeQFcBwXagbZd", $user->getData()->getApiToken());
        $this->assertEquals(0, $user->getData()->getFlags());
        $this->assertEquals([], $user->getData()->getBetaFlags());
        
        $this->addResponse($this->getFixture("projects/list/multiple.json"));
        $projects = $user->getProjects();
        $this->assertCount(1, $projects);
        $project = $projects[0];
        $this->assertInstanceOf(Project::class, $project);
        $this->assertSoundMuffler($project->getData());
    }
}