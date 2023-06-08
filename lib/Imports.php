<?php

namespace Zamzar;

class Imports extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

    public function create($params)
    {
        $apiResponse = $this->apiRequest(Import::classUrl(), 'POST', $params);

        $data = $apiResponse->getBody();

        return Import::constructFrom($data, $this->config);
    }

    public function get($id)
    {
        $apiResponse = $this->apiRequest(Import::resourceUrl($id), 'GET');

        $data = (array)$apiResponse->getBody();

        return Import::constructFrom($data, $this->config);
    }

    public function all($requestOptions = null)
    {
        $endpoint = Import::classUrl();

        if (!$requestOptions == null) {
            $endpoint = $endpoint . '/?' . http_build_query($requestOptions);
        }

        $apiResponse = $this->apiRequest($endpoint);

        $data = $apiResponse->getData();

        $this->resetData();
        foreach ($data as $object) {
            $this->addData(Import::constructFrom((array)$object, $this->config));
        }

        return $this;
    }
}
