<?php

namespace Tests;

use Zamzar\ZamzarClient;

trait WithClient
{
    /** @var ZamzarClient */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new ZamzarClient(['api_key' => $this->apiKey()]);
    }

    protected function apiKey()
    {
        return getenv('ZAMZAR_API_KEY');
    }
}
