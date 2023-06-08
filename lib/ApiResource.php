<?php

namespace Zamzar;

abstract class ApiResource extends ZamzarObject
{
    public static function classUrl()
    {
        $base = str_replace('zamzar\\', '', strtolower(static::class));
        return "/v1/{$base}s";
    }

    public static function resourceUrl($id)
    {
        $base = static::classUrl();
        return "{$base}/{$id}";
    }

    public function instanceUrl()
    {
        return static::resourceUrl($this->id);
    }

    protected function request($method, $url, $params = [], $getFileContent = false)
    {
        $response = (new ApiRequestor($this->config))->request($url, $method, $params, $getFileContent);

        return ZamzarClient::$lastResponse = $response;
    }

    public function refresh()
    {
        $response = $this->request('GET', $this->instanceUrl());
        $this->refreshFrom($response->getBody());

        return $this;
    }
}
