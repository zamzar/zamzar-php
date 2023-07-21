<?php

namespace Zamzar;

class ZamzarObject
{
    protected $values = [];
    protected $config = [];
    protected $propertyMap = [];

    public function __construct($id = null, array $config = [])
    {
        $this->config = $config;

        if ($id !== null) {
            $this->values['id'] = $id;
        }
    }

    public static function constructFrom(array $values, array $config)
    {
        $obj = new static($values['id'] ?? null, $config);
        $obj->refreshFrom($values);

        return $obj;
    }

    public function refreshFrom(array $values)
    {
        $this->values = $values;
        $this->build();
    }

    protected function build()
    {
        foreach ($this->propertyMap as $key => $class) {
            if (array_key_exists($key, $this->values)) {
                if (is_array($class)) {
                    $this->values[$key] = array_map(function ($obj) use ($class) {
                        return $class[0]::constructFrom($obj, $this->config);
                    }, $this->values[$key]);
                } else {
                    $this->values[$key] = $class::constructFrom($this->values[$key], $this->config);
                }
            }
        }
    }

    public function __get($name)
    {
        return $this->values[$name] ?? null;
    }
}
