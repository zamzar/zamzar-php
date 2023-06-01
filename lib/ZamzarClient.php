<?php

namespace Zamzar;

use Zamzar\Util\Core;
use Zamzar\Util\Logger;

/**
 *
 * The ZamzarClient is a simpler wrapper around collection classes from which all high level endpoints are exposed:
 *
 *     Inherits:
 *
 *         - 'ApiResource' contains key properties and methods which all endpoint based classes use (Account, Formats, Files, Imports, Jobs)
 *
 *     The intended usage in which the config array is created and passed correctly to other objects looks like:
 *
 *         // An api key can be provided as a string which is then merged with a default config array and validated
 *         $zamzar = new \Zamzar\ZamzarClient('apikey');
 *
 *         // An api key and other parameters can be provided as a config array which is then merged with a default config array and validated
 *         $zamzar = new \Zamzar\ZamzarClient([
 *             api_key' => 'apikey',
 *             'environment' => 'sandbox' | 'production' (default)
 *         ]);
 *
 *         // Test the Connection
 *         echo $zamzar->testConnection();
 */
class ZamzarClient extends ApiResource
{
    /** Collection objects representing the main endpoints */
    private $account;
    private $files;
    private $formats;
    private $imports;
    private $jobs;

    /**
     * Initialises a new instance of the Zamzar class
     */
    public function __construct($config)
    {
        parent::__construct($config);

        $this->config['client'] = $this;
    }

    /**
     * Setting the logs stores a reference to the logger which is written to in /Util/Logger/Logger using Logger::Log
     */
    public function setLogger(&$logger)
    {
        // set the logger within the config array as a reference to the supplied logger
        $this->config['zamzar_client_logger'] = &$logger;
        core::zamzarClientSetLogger($this->config, $logger);
    }

    /**
     * Get the Logger
     */
    public function getLogger()
    {
        return core::zamzarClientGetLogger($this->config);
    }

    /**
     * Test the connection to the Api and return the welcome message
     */
    public function testConnection()
    {
        $apiResponse = $this->apiRequest($this->getEndpoint());
        $data = $apiResponse->getBody();
        return $data->message;
    }

    /**
     * Get the current amount of production credits remaining from the API
     */
    public function getProductionCreditsRemaining()
    {
        return $this->account->get()->getProductionCreditsRemaining();
    }

    /**
     * Get the current amount of test credits remaining from the API
     */
    public function getTestCreditsRemaining()
    {
        return $this->account->get()->getTestCreditsRemaining();
    }

    /**
     * Get the production credits remaining at the time of the last API call.
     * This may be out of date; consider using `getProductionCreditsRemaining()`
     * for an up-to-date value.
     */
    public function getLastProductionCreditsRemaining()
    {
        return $this->hasLastResponse()
            ? $this->getLastResponse()->getProductionCreditsRemaining()
            : $this->getProductionCreditsRemaining();
    }

    /**
     * Get the test credits remaining at the time of the last API call.
     * This may be out of date; consider using `getTestCreditsRemaining()`
     * for an up-to-date value.
     */
    public function getLastTestCreditsRemaining()
    {
        return $this->hasLastResponse()
            ? $this->getLastResponse()->getTestCreditsRemaining()
            : $this->getTestCreditsRemaining();
    }

    /**
     * Overloaded operator to allow objects to be referenced without parentheses
     * Objects are also instantiated dynamically when needed
     * If the user adds parentheses, this is also catered for below
     */
    public function __get($key)
    {
        if (in_array($key, ["account", "files", "formats", "imports", "jobs"])) {
            $nsObject = '\\Zamzar\\' . ucwords($key);
            if (!isset($this->$key)) {
                $this->$key = new $nsObject($this->getConfig());
            }
            return $this->$key;
        }
    }

    /**
     * Instantiate and use the Account object
     * e.g. $zamzar->account()
     */
    public function account()
    {
        if (!isset($this->account)) {
            $this->account = new \Zamzar\Account($this->getConfig());
        }
        return $this->account;
    }

    /**
     * Instantiate and use the Files object
     * e.g. $zamzar->files()
     */
    public function files()
    {
        if (!isset($this->files)) {
            $this->files = new \Zamzar\Files($this->getConfig());
        }
        return $this->files;
    }

    /**
     * Instantiate and use the Formats object
     * e.g. $zamzar->formats()
     */
    public function formats()
    {
        if (!isset($this->formats)) {
            $this->formats = new \Zamzar\Formats($this->getConfig());
        }
        return $this->formats;
    }

    /**
     * Instantiate and use the Imports object
     * e.g. $zamzar->imports()
     */
    public function imports()
    {
        if (!isset($this->imports)) {
            $this->imports = new \Zamzar\Imports($this->getConfig());
        }
        return $this->imports;
    }

    /**
     * Instantiate and use the Jobs object
     * e.g. $zamzar->jobs()
     */
    public function jobs()
    {
        if (!isset($this->jobs)) {
            $this->jobs = new \Zamzar\Jobs($this->getConfig());
        }
        return $this->jobs;
    }
}
