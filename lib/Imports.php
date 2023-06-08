<?php

namespace Zamzar;

class Imports extends InteractsWithApi
{
    public function create($params)
    {
        $response = $this->apiRequest(Import::classUrl(), 'POST', $params);

        return Import::constructFrom($response->getBody(), $this->config);
    }

    public function get($id)
    {
        $response = $this->apiRequest(Import::resourceUrl($id), 'GET');

        return Import::constructFrom($response->getBody(), $this->config);
    }

    /**
     * @return \Zamzar\Collection<\Zamzar\Import>
     */
    public function all($params = [])
    {
        $response = $this->apiRequest(Import::classUrl(), 'GET', $params);

        return Collection::constructFrom($response->getBody(), $this->config, Import::classUrl(), Import::class);
    }
}
