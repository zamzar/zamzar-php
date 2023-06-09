<?php

namespace Zamzar\Service;

use Zamzar\Collection;
use Zamzar\Import;

class ImportService extends AbstractService
{
    public function create($params)
    {
        $response = $this->client->request('POST', Import::classUrl(), $params);

        return Import::constructFrom($response->getBody(), $this->client->getConfig());
    }

    public function get($id)
    {
        $response = $this->client->request('GET', Import::resourceUrl($id));

        return Import::constructFrom($response->getBody(), $this->client->getConfig());
    }

    /**
     * @return \Zamzar\Collection<\Zamzar\Import>
     */
    public function all($params = [])
    {
        $response = $this->client->request('GET', Import::classUrl(), $params);

        return Collection::constructFrom($response->getBody(), $this->client->getConfig(), Import::classUrl(), Import::class);
    }
}
