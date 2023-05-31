<?php

namespace Zamzar;

use Util\Logger;

/**
 * ApiResponse Class
 *
 * Stores Body, Code and Headers from HTTP requests
 * Helper functions are provided to help split the data & paging arrays which can be received in the body
 */
class ApiResponse
{
    /** Properties */
    private $body;
    private $code;
    private $headers;

    public function __construct($body, $code, $headers)
    {
        $this->body = json_decode($body);
        $this->code = $code;
        $this->headers = $headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getBodyRaw()
    {
        return json_encode($this->body);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getData()
    {
        if (is_object($this->body)) {
            if (property_exists($this->body, "data")) {
                return $this->body->data;
            } else {
                return null;
            }
        }
    }

    public function getPaging()
    {
        if (is_object($this->body)) {
            if (property_exists($this->body, "paging")) {
                return $this->body->paging;
            } else {
                return null;
            }
        }
    }

    public function getProductionCreditsRemaining()
    {
        if (array_key_exists("Zamzar-Credits-Remaining", $this->getHeaders())) {
            return $this->headers['Zamzar-Credits-Remaining'][0];
        } else {
            return -1;
        }
    }

    public function getTestCreditsRemaining()
    {
        if (array_key_exists("Zamzar-Test-Credits-Remaining", $this->getHeaders())) {
            return $this->headers['Zamzar-Test-Credits-Remaining'][0];
        } else {
            return -1;
        }
    }
}
