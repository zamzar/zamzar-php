<?php

namespace Zamzar\Contracts;

interface ClientInterface
{
    public function getConfig();

    /**
     * @return \Zamzar\ApiResponse
     */
    public function request($method, $uri, $params = [], $getFileContent = false);
}
