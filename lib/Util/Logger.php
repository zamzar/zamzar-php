<?php

namespace Zamzar\Util;

class Logger
{
    /**
     * @var null|LoggerInterface The logger to which the library will
     *   produce messages
     */
    protected static $logger = null;

    /**
     * @return LoggerInterface the logger to which the library will
     *   produce messages
     */
    public static function getLogger()
    {
        return self::$logger ?? new DefaultLogger();
    }

    /**
     * @param LoggerInterface $logger The logger to which the library
     *   will produce messages
     */
    public static function setLogger($logger)
    {
        self::$logger = $logger;
    }
}
