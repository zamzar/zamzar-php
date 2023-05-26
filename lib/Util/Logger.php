<?php

namespace Zamzar\Util;

/**
 * Logger
 * Outputs log information to an array or a psr3 compatible logger both of which are provided when Zamzarclient is instantiated
 * Supports errors, warnings and notices
 */
class Logger
{
    /**
     * Add an entry to the log
     */
    public static function log(&$config, $message, $messageType = 0)
    {
        if (array_key_exists("zamzar_client_logger", $config)) {
            // Get a reference to the logger (which can be a simple array or a psr-3 compatible logger)
            $logger = &$config['zamzar_client_logger'];

            // Output the message and optionally the messagetype
            $date = date("D M d, Y G:i");

            if (is_array($logger)) {
                $logger[] = $date . ' ' . $message;
            } else {
                // should be a reference to a logger object which uses the standard psr-3 interface
                // i.e. either an instance of our own DefaultLogger or one provided by user
                switch ($messageType) {
                    case 0:
                        $logger->info($message);
                        break;
                    case E_ERROR:
                        $logger->error($message);
                        break;
                    default:
                        $logger->info($message);
                        break;
                }
            }
        }
    }
}
