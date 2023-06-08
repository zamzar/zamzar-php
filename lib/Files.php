<?php

namespace Zamzar;

class Files extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

    public function create($params)
    {
        $apiResponse = $this->apiRequest(File::classUrl(), 'POST', $params);

        $data = $apiResponse->getBody();

        return File::constructFrom((array)$data, $this->config);
    }

    public function get($id)
    {
        $apiResponse = $this->apiRequest(File::resourceUrl($id), 'GET');

        $data = $apiResponse->getBody();

        return File::constructFrom((array)$data, $this->config);
    }

    public function all($requestOptions = null)
    {
        $endpoint = File::classUrl();

        if (!$requestOptions == null) {
            $endpoint = $endpoint . '/?' . http_build_query($requestOptions);
        }

        $apiResponse = $this->apiRequest($endpoint);

        $data = $apiResponse->getData();

        $this->resetData();
        foreach ($data as $object) {
            $this->addData(File::constructFrom((array)$object, $this->config));
        }

        return $this;
    }
}
