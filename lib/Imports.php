<?php

namespace Zamzar;

class Imports extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

    public function create($params)
    {
        $apiResponse = $this->apiRequest(Import::classUrl(), 'POST', $params);

        $data = $apiResponse->getBody();

        return Import::constructFrom((array)$data, $this->config);
    }

    public function get($id)
    {
        $apiResponse = $this->apiRequest(Import::resourceUrl($id), 'GET');

        $data = $apiResponse->getBody();

        return Import::constructFrom((array)$data, $this->config);
    }

    public function all($params = [])
    {
        $apiResponse = $this->apiRequest(Import::classUrl(), 'GET', $params);

        $data = $apiResponse->getData();

        $this->resetData();
        foreach ($data as $object) {
            $this->addData(Import::constructFrom((array)$object, $this->config));
        }

        return $this;
    }
}
