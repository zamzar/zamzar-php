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

    public function all($params = [])
    {
        $apiResponse = $this->apiRequest(Format::classUrl(), 'GET', $params);

        $data = $apiResponse->getData();

        $this->resetData();
        foreach ($data as $object) {
            $this->addData(Format::constructFrom((array)$object, $this->config));
        }

        return $this;
    }
}
