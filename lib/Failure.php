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
     * @deprecated Access property directly instead
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getMessage()
    {
        return $this->message;
    }
}
