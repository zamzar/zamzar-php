<?php

namespace Zamzar;

use Zamzar\Util\DefaultLogger;

class Zamzar
{
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
