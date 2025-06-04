<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Zamzar\HttpClient\GuzzleClient;

final class GuzzleClientTest extends TestCase
{
    public function testRetries()
    {
        $mock = new MockHandler([
            new Response(504),
            new Response(504),
            new Response(200, [], 'OK')
        ]);

        $historyContainer = [];
        $history = Middleware::history($historyContainer);

        $stack = GuzzleClient::recommendedStack($mock);
        $stack->push($history);

        $client = new Client(['handler' => $stack]);

        $response = $client->get('/some-endpoint');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(3, $historyContainer, "Should have tried twice before success");
    }

    public function testRetriesDoNotExceedMaximum()
    {
        $mock = new MockHandler([
            new Response(504),
            new Response(504),
            new Response(200, [], 'OK')
        ]);

        $historyContainer = [];
        $history = Middleware::history($historyContainer);

        $stack = GuzzleClient::recommendedStack($mock, 1);
        $stack->push($history);

        $client = new Client(['handler' => $stack]);

        $response = $client->get('/some-endpoint');

        $this->assertEquals(504, $response->getStatusCode());
        $this->assertCount(2, $historyContainer, "Should have tried exactly twice");
    }

    public function testRetriesAreUnnecessaryWhenResponseIsOk()
    {
        $mock = new MockHandler([
            new Response(200, [], 'OK')
        ]);

        $historyContainer = [];
        $history = Middleware::history($historyContainer);

        $stack = GuzzleClient::recommendedStack($mock);
        $stack->push($history);

        $client = new Client(['handler' => $stack]);

        $response = $client->get('/some-endpoint');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $historyContainer, "Should have tried exactly once");
    }
}