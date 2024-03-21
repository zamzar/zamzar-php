<?php

namespace Zamzar;

use Zamzar\HttpClient\GuzzleClient;

/**
 * ApiRequestor Class
 *
 * Acts as a wrapper around all api requests which are submitted via the GuzzleClient
 * All requests are checked before being submitted
 * All responses are interrogated to determine if any exceptions should be thrown
 * Returns the response to the caller
 */
class ApiRequestor
{
    /** Properties */
    private $config;
    private $endpoint;
    private $method;
    private $params;
    private $getFileContent;
    private $hasLocalFile;
    private $apiResponse;

    /**
     * Initialise a new instance of the ApiRequestor with a config array containing the api key and other config values
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Submit the request
     */
    public function request($endpoint, $method = 'GET', $params = [], $getFileContent = false)
    {
        $base = $this->config['base_url'] ?? Zamzar::API_BASE[$this->config['environment']];
        $endpoint = $base . $endpoint;

        if ($this->config['debug']) {
            Zamzar::getLogger()->info("($method) $endpoint params=" . json_encode($params));
        }

        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->params = $params;
        $this->getFileContent = $getFileContent;

        // Check the request parameters before the request is submitted
        // During the checks, the $hasLocalFile semaphore will be set to true if a local file is being used in the request
        $this->checkRequest();

        // Make the request via Guzzle Client - an ApiResponse object will be returned
        $this->apiResponse = GuzzleClient::request($this->config, $this->endpoint, $this->method, $this->params, $this->hasLocalFile, $getFileContent);

        // Check the response
        $this->checkResponse();

        // Return the response
        return $this->apiResponse;
    }

    /**
     * Validate Request
     */
    private function checkRequest()
    {
        switch ($this->method) {
            case "GET":
                // Download a file
                if ($this->getFileContent) {
                    // Check a local path or filename has been supplied
                    if (empty($this->params) || !array_key_exists('download_path', $this->params)) {
                        $msg = "To download a file(s), specify a path within the params, in the form ['download_path' => 'local/file/path']";
                        throw new \Zamzar\Exception\InvalidArgumentException($msg);
                    }

                    // If a local path, ensure it's correctly formatted and the directory exists.
                    if (!is_dir(dirname($this->params["download_path"]))) {
                        $msg = "The path specified does not exist. Specify a valid path within the params in the form ['download_path' => 'valid/local/file/path']";
                        throw new \Zamzar\Exception\InvalidArgumentException($msg);
                    }
                }
                break;
            case "POST":
                // Send a file
                if (strpos($this->endpoint, '/files')) {
                    // Throw an error if the name isn't specified
                    if (!array_key_exists("name", $this->params)) {
                        throw new \Zamzar\Exception\InvalidArgumentException("name must be specified and be a local file.");
                    }

                    // Throw an error if an invalid file name
                    if (!$this->sourceFileIsValid($this->params["name"])) {
                        throw new \Zamzar\Exception\InvalidArgumentException("name must be a local file which exists.");
                    }
                }

                // Initiate an Import
                if (strpos($this->endpoint, '/imports')) {
                    // Throw an error if the import url is not specified
                    if (!array_key_exists("url", $this->params)) {
                        throw new \Zamzar\Exception\InvalidArgumentException("url is not specified.");
                    }

                    // Throw an error if the import url is invalid
                    if (!$this->importUrlIsValid($this->params["url"])) {
                        throw new \Zamzar\Exception\InvalidArgumentException("url is incorrectly specified or unsupported - must begin with http(s), (s)ftp or s3");
                    }
                }

                // Submit a Job
                if (strpos($this->endpoint, '/jobs')) {
                    // Is a source file specified
                    if (!array_key_exists("source_file", $this->params)) {
                        throw new \Zamzar\Exception\InvalidArgumentException("source_file must be specified and be either a local file (which exists) or a supported type of remote file.");
                    }

                    // Is the source file valid
                    if (!$this->sourceFileIsValid($this->params["source_file"])) {
                        throw new \Zamzar\Exception\InvalidArgumentException("source_file must be a local file which exists or a supported type of remote file.");
                    }

                    // Is a target format specified
                    if (!array_key_exists("target_format", $this->params)) {
                        throw new \Zamzar\Exception\InvalidArgumentException("target_format must be a valid conversion format for this type of file.");
                    }

                    // Is the export url valid if specified
                    if (array_key_exists("export_url", $this->params)) {
                        if (!$this->exportUrlIsValid($this->params["export_url"])) {
                            throw new \Zamzar\Exception\InvalidArgumentException("export_url is incorrectly specified or unsupported, must begin with (s)ftp or s3");
                        }
                    }

                    // Does the local file path exist if specified
                    if (array_key_exists("download_path", $this->params)) {
                        if (!is_dir($this->params['download_path'])) {
                            $msg = 'The download_path is not valid';
                            throw new \Zamzar\Exception\InvalidArgumentException($msg);
                        }
                    }

                    // Has the deletion option been correctly specified
                    if (array_key_exists("delete_files", $this->params)) {
                        $delete_files = $this->params["delete_files"];
                        if (!($delete_files == 'all' || $delete_files == 'source' || $delete_files == 'target')) {
                            $msg = "delete_files should be set to 'all', 'source' or 'target'";
                            throw new \Zamzar\Exception\InvalidArgumentException($msg);
                        }
                    }
                }
        }
    }

    /**
     * Check if supplied file is local and exists
     */
    private function isLocalFile($sourceFile)
    {
        $isLocalFile = false;
        if (!is_integer($sourceFile) && !strpos($sourceFile, "//")) {
            if (file_exists($sourceFile)) {
                $isLocalFile = true;
            }
        }

        // hasLocalFile is a parameter sent to the http client which indicates that a file should be attached to the request
        $this->hasLocalFile = $isLocalFile;
        return $isLocalFile;
    }

    /**
     * Validate Source File
     */
    private function sourceFileIsValid($sourceFile)
    {
        $valid = false;
        if ($this->isLocalFile($sourceFile)) {
            $valid = true;
        } else if (is_integer($sourceFile) || preg_match('/^(http|https|ftp|sftp|s3):\/\//i', $sourceFile) === 1) {
            $valid = true;
        }
        return $valid;
    }

    /**
     * Validate Export URL
     */
    private function exportUrlIsValid($exportUrl)
    {
        return preg_match('/^(ftp|sftp|s3):\/\//i', $exportUrl) === 1 ? true : false;
    }

    /**
     * Validate Import URL
     */
    private function importUrlIsValid($importUrl)
    {
        return preg_match('/^(http|https|ftp|sftp|s3):\/\//i', $importUrl) === 1 ? true : false;
    }

    /**
     * Check a response code and generate an api exception if appropriate
     */
    private function checkResponse()
    {
        // Return if the response code is <= 400
        if ($this->apiResponse->getCode() < 400) {
            return;
        }

        // Get the values being checked
        $responseCode = $this->apiResponse->getCode();
        $config = $this->config;
        $endpoint = $this->endpoint;

        // Check for null in the response level down in case there's been an unknown issue or timeout
        $errors = [];
        if (!is_null($this->apiResponse)) {
            if (!is_null($this->apiResponse->getBody())) {
                if (isset($this->apiResponse->getBody()['errors'])) {
                    $errors = $this->apiResponse->getBody()['errors'];
                }
            }
        }

        // Check the response codes
        $msg = '';
        switch ($this->apiResponse->getCode()) {
            case 200:
                // $msg = "Ok"... documented for completeness
                return;
                break;
            case 201:
                // $msg = "Resource created"... documented for completeness
                return;
                break;
            case 204:
                // $msg = "No content - body of response is empty"... documented for completeness
                return;
                break;
            case 307:
                // $msg = "Temporary Redirect"... documented for completeness
                return;
                break;
            case 401:
                $msg = "Forbidden - The request is not using authentication. Login to https://developers.zamzar.com to check your api key.";
                throw new \Zamzar\Exception\AuthenticationException($config, $endpoint, $errors, $msg, $responseCode);
                break;
            case 402:
                $msg = "The account does not have sufficient credit. Login to https://developers.zamzar.com to check your account status.";
                throw new \Zamzar\Exception\AccountException($config, $endpoint, $errors, $msg, $responseCode);
                break;
            case 404:
                $msg = "The requested resource does not exist. The endpoint " . $endpoint . " should be checked for validity";
                throw new \Zamzar\Exception\InvalidResourceException($config, $endpoint, $errors, $msg, $responseCode);
                break;
            case 410:
                $msg = "The requested resource is no longer available. The endpoint " . $endpoint . " should be checked for validity.";
                throw new \Zamzar\Exception\InvalidResourceException($config, $endpoint, $errors, $msg, $responseCode);
                break;
            case 413:
                $msg = "Payload too large, typically because a source file exceeds the maximum file size for this account.";
                throw new \Zamzar\Exception\PayloadException($config, $endpoint, $errors, $msg, $responseCode);
                break;
            case 422:
                $msg = "The request was malformed, for example, missing a required parameter";
                throw new \Zamzar\Exception\InvalidRequestException($config, $endpoint, $errors, $msg, $responseCode);
                break;
            case 429:
                $msg = "Too many requests. The request has exceeded a rate limit.";
                throw new \Zamzar\Exception\RateLimitException($config, $endpoint, $errors, $msg, $responseCode);
                break;
            case 500:
                $msg = "Internal Server Error - an unexpected internal error has occurred. Please wait and try again later.";
                throw new \Zamzar\Exception\UnknownApiErrorException($config, $endpoint, $errors, $msg, $responseCode);
                break;
            case 503:
                $msg = "The Api is unavailable. Please wait and try again later.";
                throw new \Zamzar\Exception\UnknownApiErrorException($config, $endpoint, $errors, $msg, $responseCode);
                break;
        }

        // if we get to here and an exception has not been thrown, then throw an unknown error exception
        throw new \Zamzar\Exception\UnknownApiErrorException($config, $endpoint, $errors, $msg, $responseCode);
    }
}
