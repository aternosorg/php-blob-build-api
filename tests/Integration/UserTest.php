<?php

namespace Aternos\BlobBuild\Test\Integration;

class UserTest extends IntegrationTestCase
{
    public function testGetAuthenticatedUser(): void
    {
        if (!$this->apiToken) {
            $this->markTestSkipped("No API token provided");
        }
        $user = $this->client->getAuthenticatedUser();
        $this->assertEquals($this->apiToken, $user->getData()->getApiToken());
        $this->assertEmpty($user->getProjects());
    }
}