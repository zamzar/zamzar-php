<?php

namespace Zamzar;

class ZamzarObject
{
    protected array $values = [];
    protected array $config = [];
    protected array $propertyMap = [];

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
                if (is_array($this->values[$key])) {
                    $this->values[$key] = array_map(function ($obj) use ($class) {
                        return $class::constructFrom((array)$obj, $this->config);
                    }, $this->values[$key]);
                } else {
                    $this->values[$key] = $class::constructFrom((array)$this->values[$key], $this->config);
                }
            }
        }
    }

    public function __get($name)
    {
        return $this->values[$name] ?? null;
    }
}
