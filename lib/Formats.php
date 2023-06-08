<?php

namespace Zamzar;

class Formats extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

    public function get($id)
    {
        $apiResponse = $this->apiRequest(Format::resourceUrl($id), 'GET');

        $data = $apiResponse->getBody();

        return Format::constructFrom((array)$data, $this->config);
    }

    public function all($requestOptions = null)
    {
        $endpoint = Format::classUrl();

        if (!$requestOptions == null) {
            $endpoint = $endpoint . '/?' . http_build_query($requestOptions);
        }

        $apiResponse = $this->apiRequest($endpoint);

        $data = $apiResponse->getData();

        $this->resetData();
        foreach ($data as $object) {
            $this->addData(Format::constructFrom((array)$object, $this->config));
        }

        return $this;
    }
}
