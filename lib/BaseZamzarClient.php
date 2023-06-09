<?php

namespace Zamzar;

use Zamzar\Contracts\ClientInterface;
use Zamzar\Exception\InvalidArgumentException;

class BaseZamzarClient implements ClientInterface
{
    protected const DEFAULT_CONFIG = [
        'api_key' => null,
        'environment' => 'production',
        'api_version' => Zamzar::API_VERSION,
        'user_agent' => Zamzar::USER_AGENT,
        'http_debug' => false,
        'debug' => false,
    ];

    /** @var null|\Zamzar\ApiResponse */
    public static $lastResponse = null;

    protected $config;

    public function __construct($config)
    {
        if (is_string($config)) {
            $config = ['api_key' => $config];
        } elseif (!is_array($config)) {
            throw new InvalidArgumentException('$config must be a string containing an api key or an array');
        }

        $config = array_merge(self::DEFAULT_CONFIG, $config);

        $this->validateConfig($config);

        $this->config = $config;
    }

    protected function validateConfig($config)
    {
        if (!\is_string($config['api_key']) || is_null($config['api_key'])) {
            $msg = 'api_key must be a string with a valid api key';
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        if ($config['api_key'] === '') {
            $msg = 'api_key cannot be an empty string';
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        if (\preg_match('/\s/', $config['api_key'])) {
            $msg = 'api_key cannot contain whitespace';
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        if (strcmp($config['environment'], 'production') !== 0 && strcmp($config['environment'], 'sandbox') !== 0) {
            $msg = "environment must be 'production' or 'sandbox'";
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        if (strcmp($config['api_version'], Zamzar::API_VERSION) != 0) {
            $msg = 'api_version must be ' . Zamzar::API_VERSION;
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        if (strcmp($config['user_agent'], Zamzar::USER_AGENT) != 0) {
            $msg = 'user_agent must be ' . Zamzar::USER_AGENT;
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        if (!is_bool($config['debug'])) {
            $msg = 'debug must be a boolean';
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function request($method, $url, $params = [], $getFileContent = false)
    {
        $response = (new ApiRequestor($this->config))->request($url, $method, $params, $getFileContent);

        return static::$lastResponse = $response;
    }

    public static function hasLastResponse()
    {
        return is_null(static::$lastResponse);
    }
}
