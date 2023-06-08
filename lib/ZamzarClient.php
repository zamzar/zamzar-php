<?php

namespace Zamzar;

/**
 * @property \Zamzar\Account $account
 * @property \Zamzar\Files $files
 * @property \Zamzar\Formats $formats
 * @property \Zamzar\Imports $imports
 * @property \Zamzar\Jobs $jobs
 */
class ZamzarClient extends InteractsWithApi
{
    private static $classMap = [
        'account' => Account::class,
        'files' => Files::class,
        'formats' => Formats::class,
        'imports' => Imports::class,
        'jobs' => Jobs::class,
    ];

    private $services = [];

    /**
     * Initialises a new instance of the Zamzar class
     */
    public function __construct($config)
    {
        parent::__construct($config);

        $this->config['client'] = $this;
    }

    /**
     * @deprecated Use \Zamzar\Zamzar::setLogger() instead.
     * @param Util\LoggerInterface $logger the logger to which the library
     *   will produce messages
     */
    public function setLogger($logger)
    {
        Zamzar::setLogger($logger);
    }

    /**
     * @deprecated Use \Zamzar\Zamzar::getLogger() instead.
     * @return Util\LoggerInterface the logger to which the library will
     *   produce messages
     */
    public function getLogger()
    {
        return Zamzar::getLogger();
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

    private function getServiceClass($key)
    {
        return self::$classMap[$key] ?? null;
    }

    public function __get($name)
    {
        if (($serviceClass = $this->getServiceClass($name)) !== null) {
            if (!array_key_exists($name, $this->services)) {
                $this->services[$name] = new $serviceClass($this->config);
            }

            return $this->services[$name];
        }
    }

    public function __call($name, $arguments)
    {
        return $this->__get($name);
    }
}
