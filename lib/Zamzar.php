<?php

namespace Zamzar;

use Zamzar\Util\DefaultLogger;

class Zamzar
{
    public const API_BASE = [
        'production' => 'https://api.zamzar.com',
        'sandbox' => 'https://sandbox.zamzar.com',
    ];

    public const API_VERSION = 'v1';
    public const USER_AGENT = 'zamzar-php-v2';

    /** Endpoint constants */
    public const ACCOUNT_ENDPOINT = 'account';
    public const FILES_ENDPOINT = 'files';
    public const FILE_CONTENT_ENDPOINT = 'content';
    public const FORMATS_ENDPOINT = 'formats';
    public const IMPORTS_ENDPOINT = 'imports';
    public const JOBS_ENDPOINT = 'jobs';

    /** Class => EndPoint */
    public const CLASS_ENDPOINT_MAP = [
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

    /** CollectionClass => Class */
    public const COLLECTION_CLASS_MAP = [
        'Zamzar\Files' => 'Zamzar\File',
        'Zamzar\Formats' => 'Zamzar\Format',
        'Zamzar\Imports' => 'Zamzar\Import',
        'Zamzar\Jobs' => 'Zamzar\Job',
    ];

    /**
     * @var null|Util\LoggerInterface The logger to which the library will
     *   produce messages
     */
    protected static $logger = null;

    /**
     * @return Util\LoggerInterface the logger to which the library will
     *   produce messages
     */
    public static function getLogger()
    {
        return self::$logger ?? new DefaultLogger();
    }

    /**
     * @param Util\LoggerInterface $logger The logger to which the library
     *   will produce messages
     */
    public static function setLogger($logger)
    {
        self::$logger = $logger;
    }
}
