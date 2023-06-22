<?php

namespace Zamzar\Service;

use Zamzar\Account;

class AccountService extends AbstractService
{
    public function get()
    {
        $apiResponse = $this->client->request('GET', Account::classUrl());

        return Account::constructFrom($apiResponse->getBody(), $this->client->getConfig());
    }
}
