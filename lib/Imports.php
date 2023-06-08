<?php

namespace Zamzar;

class Imports extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

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

    public function all($params = [])
    {
        $response = $this->apiRequest(Import::classUrl(), 'GET', $params);

        $this->resetData();
        foreach ($response->getData() as $object) {
            $this->addData(Import::constructFrom($object, $this->config));
        }

        return $this;
    }
}
