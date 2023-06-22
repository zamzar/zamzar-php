<?php

namespace Zamzar;

/**
 * Error Class
 *
 * Stores custom errors returned from the API
 */
class Error
{
    private $code;
    private $message;
    private $context = [];

    /**
     * Initialises a new instance of the Error object
     */
    public function __construct($data)
    {
        $this->setValues($data);
    }

    private function setValues($data)
    {
        $this->code = $data['code'];
        $this->message = $data['message'];

        // Optionally supplied
        if (isset($data["context"])) {
            foreach ($data['context'] as $context) {
                $this->context[] = $context;
            }
        }
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

    /**
     * Get the value of context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Does the error have context (variable array of strings describing the context of the error)
     */
    public function hasContext()
    {
        return \count($this->context) == 0 ? false : true;
    }
}
