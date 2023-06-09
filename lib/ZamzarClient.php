<?php

namespace Zamzar;

use Zamzar\Service\AccountService;
use Zamzar\Service\FileService;
use Zamzar\Service\FormatService;
use Zamzar\Service\ImportService;
use Zamzar\Service\JobService;

/**
 * @property \Zamzar\Service\AccountService $account
 * @property \Zamzar\Service\FileService $files
 * @property \Zamzar\Service\FormatService $formats
 * @property \Zamzar\Service\ImportService $imports
 * @property \Zamzar\Service\JobService $jobs
 */
class ZamzarClient extends BaseZamzarClient
{
    private static $classMap = [
        'account' => AccountService::class,
        'files' => FileService::class,
        'formats' => FormatService::class,
        'imports' => ImportService::class,
        'jobs' => JobService::class,
    ];

    private $services = [];

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
        $apiResponse = $this->request('GET', '');
        return $apiResponse->getBody()['message'];
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
            ? static::$lastResponse->getProductionCreditsRemaining()
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
            ? static::$lastResponse->getTestCreditsRemaining()
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
                $this->services[$name] = new $serviceClass($this);
            }

            return $this->services[$name];
        }
    }

    public function __call($name, $arguments)
    {
        return $this->__get($name);
    }
}
