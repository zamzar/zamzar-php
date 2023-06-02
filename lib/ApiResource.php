<?php

namespace Zamzar;

/**
 * ApiResource Class
 *
 * The core functions and values all classes use and expose
 */
class ApiResource
{
    protected const DEFAULT_CONFIG = [
        'api_key' => null,
        'environment' => 'production',
        'api_version' => Zamzar::API_VERSION,
        'user_agent' => Zamzar::USER_AGENT,
        'http_debug' => false,
        'debug' => false,
    ];

    // config is protected because it is accessed directly from subclasses
    protected $config;

    // endpoint is an object's ultimate endpoint
    private $endpoint;

    // request options relates to query parameters, e.g. limit=x, after=x, before=x
    private $requestOptions;

    // last response from every api call is stored within the object, but also stored within the ZamzarClient
    private $lastResponse;

    // data is the data returned, e.g. a list of jobs or a specific job
    public $data = array();

    // paging object for paged collections
    public $paging;

    /**
     * apiInit is called to initialise the config array and set the endpoint for any given object
     * Should be called from an object's constructor
     * $config will contain an apikey or a config array and optionally an objectid (a job id, a file id, a format name etc)
     */
    public function __construct($config, $objectId = '')
    {
        if (is_string($config)) {
            $config = ['api_key' => $config];
        } elseif (!is_array($config)) {
            throw new \Zamzar\Exception\InvalidArgumentException('$config must be a string containing an api key or an array');
        }

        $config = array_merge(self::DEFAULT_CONFIG, $config);

        $this->validateConfig($config);

        $this->config = $config;

        $this->setEndPoint($objectId);
    }

    /**
     * apiRequest is a wrapper around the apiRequestor/Response Objects
     * Make the request, save the response, and return the response
     */
    protected function apiRequest($endpoint, $method = 'GET', $params = [], $getFileContent = false, $filename = '')
    {
        // Initialise requestor
        $apiRequestor = new \Zamzar\ApiRequestor($this->getConfig());

        // Make the request and save the last response
        $this->lastResponse = $apiRequestor->request($endpoint, $method, $params, $getFileContent, $filename);

        if (isset($this->config['client'])) {
            ($this->config['client'])->setLastResponse($this->lastResponse);
        }

        // Store the paging information
        $this->paging = $this->lastResponse->getPaging();

        // Return the response
        return $this->lastResponse;
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

    /**
     * Return the config array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set an endpoint for this object
     */
    private function setEndPoint($objectId = '')
    {
        $this->endpoint = $this->getApiBaseUrl() . '/' . $this->config['api_version'] . '/';
        $this->endpoint = $this->endpoint . Zamzar::CLASS_ENDPOINT_MAP[static::class];
        if ($objectId !== '') {
            $this->endpoint = $this->endpoint . '/' . $objectId;
        }
    }

    /**
     * Set a new endpoint, used when an endpoint might deviate from the standard pattern
     * E.g. ../jobs/successful is unique in that it should probably be a query parameter
     */
    protected function setNewEndPoint($newEndpoint)
    {
        $this->endpoint = $newEndpoint;
    }

    /**
     * Get the endpoint for this object
     * Public to assist with any debugging of issues
     */
    public function getEndpoint($addFileContentEndpoint = false)
    {
        if ($addFileContentEndpoint) {
            return $this->endpoint . '/' . Zamzar::FILE_CONTENT_ENDPOINT;
        } else {
            return $this->endpoint;
        }
    }

    /**
     * Get the last response (body, headers, status)
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function hasLastResponse()
    {
        return !is_null($this->getLastResponse());
    }

    protected function setLastResponse($response)
    {
        $this->lastResponse = $response;
    }

    /**
     * Set the data
     */
    protected function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get the Data
     * Public because it's a core method for iterating through collections
     * $data is public and is preferable to getData()
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Reset the data
     */
    protected function resetData()
    {
        $this->data = array();
    }

    /**
     * Add Data
     */
    protected function addData($item)
    {
        $this->data[] = $item;
    }

    public function getApiBaseUrl()
    {
        return Zamzar::API_BASE[$this->config['environment']];
    }

    /**
     * Get a fully formed endpoint given the class name
     */
    public function getFullyFormedEndPointFromClassName($className, $objectId = '', $addFileContentEndpoint = false)
    {
        $endpoint = $this->getApiBaseUrl() . '/' . $this->config['api_version'] . '/' . Zamzar::CLASS_ENDPOINT_MAP[$className];
        if ($objectId != '') {
            $endpoint = $endpoint . '/' . $objectId;
        }
        if ($addFileContentEndpoint) {
            $endpoint = $endpoint . '/' . Zamzar::FILE_CONTENT_ENDPOINT;
        }
        return $endpoint;
    }
}
