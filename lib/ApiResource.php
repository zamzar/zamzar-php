<?php

namespace Zamzar;

use Zamzar\Util\Core;
use Zamzar\Util\Logger;

/**
 * ApiResource Class
 *
 * The core functions and values all classes use and expose
 */
class ApiResource
{
    /**
     * Properties
     */

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
        $this->setConfig($config);

        $this->setEndPoint($objectId);

        $class = str_replace("Zamzar\\", "", static::class);
        if ($objectId == '') {
            Logger::getLogger()->info('CreateObj=>' . $class . get_parent_class());
        } else {
            Logger::getLogger()->info('CreateObj=>' . $class . '=>' . $objectId);
        }
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

    /**
     * Initialise the config array given a string or an array
     */
    protected function setConfig($config)
    {
        $this->config = core::setConfig($config);
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
        $this->endpoint = $this->config['api_base'] . '/' . $this->config['api_version'] . '/';
        $this->endpoint = $this->endpoint . core::getEndPointFromClassName(static::class);
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
    public function getEndPoint($addFileContentEndpoint = false)
    {
        if ($addFileContentEndpoint) {
            return $this->endpoint . '/' . core::FILE_CONTENT_ENDPOINT;
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
}
