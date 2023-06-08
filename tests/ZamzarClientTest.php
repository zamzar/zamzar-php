<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ZamzarClientTest extends TestCase
{
    public function testEmptyApiKey(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $zamzar = new \Zamzar\ZamzarClient('');
    }

    public function testWhiteSpaceApiKey(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $zamzar = new \Zamzar\ZamzarClient('asd asd');
    }

    public function testEmptyConfigArray(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $zamzar = new \Zamzar\ZamzarClient([]);
    }

    public function testEmptyApiKeyInConfigArray(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $zamzar = new \Zamzar\ZamzarClient(['api_key' => '']);
    }

    public function testWhiteSpaceApiKeyInConfigArray(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $zamzar = new \Zamzar\ZamzarClient(['api_key' => 'white space']);
    }

    public function testInvalidEnvironment(): void
    {
        $this->expectException(\Zamzar\Exception\InvalidArgumentException::class);
        $zamzar = new \Zamzar\ZamzarClient([
            'api_key' => 'abcd1234',
            'environment' => 'invalid'
        ]);
    }

    public function testBaseEndPointProductionDefault(): void
    {
        $zamzar = new \Zamzar\ZamzarClient('abcd1234');
        $this->assertEquals($zamzar->getConfig()['environment'], 'production');
    }

    public function testProductionEnvironmentVariableTranslates(): void
    {
        $zamzar = new \Zamzar\ZamzarClient([
            'api_key' => 'abcd1234',
            'environment' => 'production'
        ]);
        $this->assertEquals($zamzar->getConfig()['environment'], 'production');
    }

    public function testTestEnvironmentVariableTranslates(): void
    {
        $zamzar = new \Zamzar\ZamzarClient([
            'api_key' => 'abcd1234',
            'environment' => 'sandbox'
        ]);
        $this->assertEquals($zamzar->getConfig()['environment'], 'sandbox');
    }
}
