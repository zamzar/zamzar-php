<?php

namespace Zamzar;

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
    private $parsedBody;

    public function __construct($body, $code, $headers)
    {
        $this->body = $body;
        $this->code = $code;
        $this->headers = $headers;
    }

    /**
     * Get the body of the response. Returns an array if the body is valid JSON.
     *
     * Note that this method will attempt to convert the entire request body to a string in memory. Callers must
     * ensure that the content length of the response is not so large that it would cause memory exhaustion. Use
     * getBodyRaw() to get the raw body without loading its entire contents into memory.
     *
     * @return array|null
     */
    public function getBody()
    {
        if (is_null($this->parsedBody)) {
            $this->parsedBody = json_decode($this->body, true);
        }

        return $this->parsedBody;
    }

    public function getBodyRaw()
    {
        return $this->body;
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
        return $this->body['data'] ?? null;
    }

    public function getPaging()
    {
        return $this->body['paging'] ?? null;
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
