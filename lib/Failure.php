<?php

namespace Zamzar;

/**
 * Failure Object
 *
 * Stores responses from the api
 */
class Failure
{
    /** Properties */
    private $code;
    private $message;

    /**
     * Initialise a new instance of the Failure object
     * Accepts code and message
     */
    public function __construct($data)
    {
        $this->setValues($data);
    }

    /**
     * Cast to string
     */
    public function __toString()
    {
        return $this->code . '-' . $this->message;
    }

    /**
     * Initialise or Update properties
     */
    private function setValues($data)
    {
        $this->code = $data->code;
        $this->message = $data->message;
    }

    /**
     * Get the value of code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
