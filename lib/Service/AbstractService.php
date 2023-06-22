<?php

namespace Zamzar\Service;

use Zamzar\Contracts\ClientInterface;

abstract class AbstractService
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
}
