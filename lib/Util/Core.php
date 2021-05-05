<?php

namespace Zamzar\Util;

/**
 * Core Class
 * 
 * Contains core constants and functions which should all be static
 */
class Core
{

    /** Base URL's */
	public const ZAMZAR_API_PRODUCTION_BASE = 'https://api.zamzar.com';
    public const ZAMZAR_API_SANDBOX_BASE = 'https://sandbox.zamzar.com';

    /** Default Config Values */
	public const API_VERSION = 'v1';
	public const USER_AGENT = 'zamzar-php-v1';

    /** Endpoint constants */
    public const ACCOUNT_ENDPOINT = 'account';
    public const FILES_ENDPOINT = 'files';
    public const FILE_CONTENT_ENDPOINT = 'content';
    public const FORMATS_ENDPOINT = 'formats';
    public const IMPORTS_ENDPOINT = 'imports';
    public const JOBS_ENDPOINT = 'jobs';

    /** CollectionClass => Class */ 
    private static $CollectionClassNameToSingularClassNameArray = [
        'Zamzar\Files' => 'Zamzar\File',
        'Zamzar\Formats' => 'Zamzar\Format',
        'Zamzar\Imports' => 'Zamzar\Import',
        'Zamzar\Jobs' => 'Zamzar\Job',
    ];

    /** Class => EndPoint */
    private static $ClassNameToEndPointArray = [
        'Zamzar\ZamzarClient' => "",
        'Zamzar\Account' => self::ACCOUNT_ENDPOINT,
        'Zamzar\Files' => self::FILES_ENDPOINT,
        'Zamzar\File' => self::FILES_ENDPOINT,
        'Zamzar\Formats' => self::FORMATS_ENDPOINT,
        'Zamzar\Format' => self::FORMATS_ENDPOINT,
        'Zamzar\Imports' => self::IMPORTS_ENDPOINT,
        'Zamzar\Import' => self::IMPORTS_ENDPOINT,
        'Zamzar\Jobs' => self::JOBS_ENDPOINT,
        'Zamzar\Job' => self::JOBS_ENDPOINT,
    ];

        
    /**
    * The Last Response from any call to the API is stored in the ZamzarClient object
    * A config array is passed into this function from which a reference to the lastresponse variable is obtained
    * Only update if it's already set, i.e. if per object instantiation is done, the lastresponse won't be available (because the ZamzarClient is not instantiated if using per-object instantiation)
    */

    /**
     * Sets the Logger Object
     * Note that this is done by reference and stored within ZamzarClient if instantiated
     */
    public static function zamzarClientSetLogger(&$config, $logger)
    {
        if(array_key_exists("zamzar_client_logger", $config)) {
            // Get the reference to the logger
            $zamzarClientLogger = &$config["zamzar_client_logger"];
            // Set the reference to the supplied logger
            $zamzarClientLogger = $logger;
        }
    }

    /**
     * Gets the Logger from the supplied Config Array
     * Note that this done by reference to the the ZamzarClient if instantiated
     */
    public static function zamzarClientGetLogger(&$config)
    {
        if(array_key_exists("zamzar_client_logger", $config)) {
            $logger = &$config["zamzar_client_logger"];
            return $logger;
        }
    }

     /**
     * Set the last response returned from api
     * Note that this is done by reference and stored within ZamzarClient if instantiated
     */
    public static function zamzarClientSetLastResponse(&$config, $lastResponse) 
    {
        if(array_key_exists("zamzar_client_last_response", $config)) {
            $zamzarClientLastResponse = &$config['zamzar_client_last_response'];
            $zamzarClientLastResponse = $lastResponse;
        }
    }

    /**
     * Get the last response returned from api
     * Note that this done by reference to the the ZamzarClient if instantiated
     */
    public static function zamzarClientGetLastResponse(&$config) 
    {
        if(array_key_exists("zamzar_client_last_response", $config)) {
            $lastResponse = &$config['zamzar_client_last_response'];
            return $lastResponse;
        }
    }

    /**
     * Get the Default Config Array
     */
	private static function getDefaultConfig()
	{
		return [
			'api_key' => null,
			'api_base' => self::ZAMZAR_API_PRODUCTION_BASE,
			'api_version' => self::API_VERSION,
			'user_agent' => self::USER_AGENT,
            'http_debug' => false,
		];
	}

    /**
     * Initialise a config array given a string or an arrray 
     */
    public static function setConfig($config) 
    {
        
        // If an api key is provided, then build the config array, otherwise check if the config is an array
		if (\is_string($config)) {

			$config = [
                        'api_key' => $config,
                        'api_base' => self::ZAMZAR_API_PRODUCTION_BASE
                      ];

		} elseif (!\is_array($config)) {
			throw new \Zamzar\Exception\InvalidArgumentException('$config must be a string containing an api key or an array');
		}

        // Check if the array has an environment key and replace it with an api base key
        $environment = '';
        if(array_key_exists('environment', $config)) {
            $environment = strtolower($config['environment']);
            switch ($environment) {
                case 'production':
                    $config['api_base'] = self::ZAMZAR_API_PRODUCTION_BASE;
                    break;
                case 'sandbox':
                    $config['api_base'] = self::ZAMZAR_API_SANDBOX_BASE;
                    break;
                default:
                    throw new \Zamzar\Exception\InvalidArgumentException('environment must be production or sandbox');
            }
            unset($config['environment']);
        }

		// Merge the config array with the defaults
		$config = \array_merge(self::getDefaultConfig(), $config);

		// Validate the config array and throw exceptions if there are any issues
		self::validateConfig($config);

        // Log the environment, this should happen only once on the initialisation of the ZamzarClient
        if($environment != '') {
            Logger::log($config, "Zamzar Client Initialised");
            Logger::log($config, "Environment=" . ucwords($environment) . ';ApiKey=' . $config['api_key']);
        }

        // Store config array
        return $config;

    }

    /** 
     * Validate a config array
     */
    private static function validateConfig($config)
    {

        $msg = null;

        // api_key
        if (!\is_string($config['api_key']) || is_null($config['api_key']) ) {
            $msg = 'api_key must be a string with a valid api key';
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        if ($config['api_key'] === '') {
            $msg = 'api_key cannot be an empty string';
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        if ($config['api_key'] !== null && (\preg_match('/\s/', $config['api_key']))) {
            $msg = 'api_key cannot contain whitespace';
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        // api_base
		if (!strcmp($config['api_base'], self::ZAMZAR_API_PRODUCTION_BASE) == 0 && !strcmp($config['api_base'], self::ZAMZAR_API_SANDBOX_BASE) == 0) {
			$msg = 'api_base must be ' . self::ZAMZAR_API_PRODUCTION_BASE . ' or ' . self::ZAMZAR_API_SANDBOX_BASE;
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
        }

        // api_version
		if (strcmp($config['api_version'], self::API_VERSION) != 0) {
            $msg = 'api_version must be ' . self::API_VERSION;
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
		}

        // user_agent
		if (strcmp($config['user_agent'], self::USER_AGENT) != 0) {
			$msg = 'user_agent must be ' . self::USER_AGENT;
            throw new \Zamzar\Exception\InvalidArgumentException($msg);
		}
    }

    /**
     * Get an end point given the class name
     */
    public static function getEndPointFromClassName($className) {
        return(self::$ClassNameToEndPointArray[$className]);
    }

    /**
     * Get Singular Class Name from Collection Class Name
     */
    public static function getSingularClassNameFromCollectionClassName ($collectionClassName) {
        return(self::$CollectionClassNameToSingularClassNameArray[$collectionClassName]);
    }

    /**
     * Get a fully formed endpoint given the class name
     */
    public static function getFullyFormedEndPointFromClassName($config, $className, $objectId = '', $addFileContentEndpoint = false) {
        $endpoint = $config['api_base'] . '/' . $config['api_version'] . '/' . self::$ClassNameToEndPointArray[$className];
        if ($objectId != '') {
            $endpoint = $endpoint . '/' . $objectId;
        }
        if ($addFileContentEndpoint) {
            $endpoint = $endpoint . '/' . self::FILE_CONTENT_ENDPOINT; 
        }
        return $endpoint;
    }

}