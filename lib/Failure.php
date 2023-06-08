<?php

namespace Zamzar;

/**
 * @property int $code
 * @property string $message
 */
class Failure extends ZamzarObject
{
    /**
     * Cast to string
     */
    public function __toString()
    {
        return $this->code . '-' . $this->message;
    }

    /**
     * Get the value of code
     * @deprecated
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the value of message
     * @deprecated
     */
    public function getMessage()
    {
        return $this->message;
    }
}
