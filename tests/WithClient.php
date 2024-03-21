<?php

namespace Tests;

use GuzzleHttp\Client as GuzzleClient;
use Zamzar\ZamzarClient;

trait WithClient
{
    /** @before */
    protected function resetMock() {
        // Construct the URL for to reset the mock
        // See: https://github.com/zamzar/zamzar-mock/blob/main/README.md
        $parts = parse_url($this->baseUrl());
        $url = $parts['scheme'] . "://" . $parts['host'] . ":" . $parts['port'] . "/__admin/scenarios/reset";

        // Send a POST request to the mock server via Guzzle
        (new GuzzleClient())->post($url);
    }

    protected function client(array $configOverrides = []): ZamzarClient
    {
        $configDefaults = [
            'api_key' => $this->apiKey(),
            'base_url' => $this->baseUrl(),
        ];

        $config = array_merge($configDefaults, $configOverrides);

        return new ZamzarClient($config);
    }

    protected function apiKey()
    {
        return getenv('ZAMZAR_API_KEY');
    }

    protected function baseUrl()
    {
        return getenv('ZAMZAR_API_URL');
    }
}
