<?php

namespace Aternos\BlobBuild\Test\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class APIClientTest extends UnitTestCase
{
    public function testSetUserAgent(): void
    {
        $this->client->setUserAgent('test-agent');
        $this->addResponse($this->getFixture("user/J3fftw.json"));
        $user = $this->client->getAuthenticatedUser();
        $this->assertEquals('J3fftw', $user->getData()->getName());

        $history = array_shift($this->requestHistory);
        $this->assertEquals('test-agent', $history['request']->getHeaderLine('User-Agent'));
    }

    public function testSetApiToken(): void
    {
        $this->client->setApiToken('test-token');
        $this->addResponse($this->getFixture("user/J3fftw.json"));
        $user = $this->client->getAuthenticatedUser();
        $this->assertEquals('J3fftw', $user->getData()->getName());
        
        $history = array_shift($this->requestHistory);
        $this->assertEquals('Bearer test-token', $history['request']->getHeaderLine('Authorization'));
    }

    public function testSetHttpClient(): void
    {
        $handlerStack = HandlerStack::create($this->httpMockHandler);
        $requestHistory = [];
        $handlerStack->push(Middleware::history($requestHistory));
        $client = new Client(['handler' => $handlerStack]);
        $this->client->setHttpClient($client);
        
        $this->addResponse($this->getFixture("user/J3fftw.json"));
        $user = $this->client->getAuthenticatedUser();
        $this->assertEquals('J3fftw', $user->getData()->getName());

        $this->assertCount(1, $requestHistory);
    }
}