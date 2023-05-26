<?php

namespace Zamzar\Exception;

use Zamzar\Util\Logger;

/**
 * ApiErrorException object which is thrown from the HTTPUtility class based on response codes
 */
class ApiErrorException extends \Exception
{
    /**
     * Custom variable to hold array of errors returned from api
     */
    private $apiErrors = array();
    private $apiErrorsRaw = array();
    private $config;
    private $endpoint;

    /**
     * Constructor accepts array of api errors in addition to standard \Exception errors
     */
    public function __construct($config, $endpoint, $errors = null, $message = null, $code = 0, \Throwable $previous = null)
    {
        // Expected when extending \Exception
        parent::__construct($message, $code, $previous);

        // Log the message
        Logger::log($config, static::class . '. ' . $message, E_ERROR);

        // Store the raw array of errors
        if (is_array($errors)) {
            $apiErrors = [];
            foreach ($errors as $error) {
                $this->apiErrors[] = new \Zamzar\Error($error);
            }
            $this->apiErrorsRaw = json_encode($errors);
        } else {
            $apiErrors = [];
            $this->apiErrors[] = new \Zamzar\Error(["message" => "No additional information provided", "code" => $code]);
            $this->apiErrorsRaw = json_encode("No additional information provided");
        }

        // Store the Config and Endpoint
        $this->config = $config;
        $this->endpoint = $endpoint;
    }

    /**
     * Return errors
     */
    public function getApiErrors()
    {
        return $this->apiErrors;
    }
    public function getApiErrorsRaw()
    {
        return $this->apiErrorsRaw;
    }
    public function getConfig()
    {
        return $this->config;
    }
    public function getEndPoint()
    {
        return $this->endpoint;
    }

    /**
     * Custom string representation of object
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
