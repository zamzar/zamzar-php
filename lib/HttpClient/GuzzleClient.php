<?php

namespace Zamzar\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;

/**
 * GuzzleClient
 * Acceps a Config Array, an Endpoint, Method (POST, GET etc), params and semaphores to guide the logic
 * Returns an ApiResponse Object containing Raw JSON Body, Code, Headers
 */
class GuzzleClient
{
    /**
     * A single request method accommodates all requests to the API
     */
    public static function request($config, $endpoint, $method = 'GET', $params = [], $hasLocalFile = false, $getFileContent = false)
    {
        $client = $config['transport'] ?? new Client();
        $response = $client->request($method, $endpoint, self::prepareRequest($config, $method, $params, $hasLocalFile));

        if (!is_null($response)) {
            return new \Zamzar\ApiResponse(
                $response->getBody(),
                $response->getStatusCode(),
                $response->getHeaders()
            );
        } else {
            // If we are here, then it's a strange place to be
            throw new \Zamzar\Exception\UnknownApiErrorException($config, $endpoint, null, 'An unexpected error has occurred. No response received');
        }
    }

    protected static function prepareRequest($config, $method, $params, $hasFile)
    {
        $options = [
            'debug' => $config['http_debug'],
            'headers' => [
                'Authorization' => 'Bearer ' . $config['api_key'],
                'User-Agent' => $config['user_agent'],
            ],
            'http_errors' => false,
            'sink' => $params['download_path'] ?? null,
        ];

        if (array_key_exists('download_path', $params)) {
            unset($params['download_path']);
        }

        if ($hasFile && array_key_exists("name", $params)) {
            $params['content'] = $params['name'];
            $params['name'] = basename($params['name']);
        }

        if ($method === 'POST') {
            foreach ($params as $key => $value) {
                $options['multipart'][] = [
                    'name' => $key,
                    'contents' => ($hasFile && ($key === 'source_file' || $key === 'content')) ? Utils::tryFopen($value, 'r') : $value,
                ];
            }
        } else {
            $options['query'] = $params;
        }

        return $options;
    }
}
