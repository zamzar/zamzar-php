<?php

namespace Zamzar;

class Formats extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

    public function get($id)
    {
        $response = $this->apiRequest(Format::resourceUrl($id), 'GET');

        return Format::constructFrom($response->getBody(), $this->config);
    }

    public function all($params = [])
    {
        $response = $this->apiRequest(Format::classUrl(), 'GET', $params);

        $this->resetData();
        foreach ($response->getData() as $object) {
            $this->addData(Format::constructFrom($object, $this->config));
        }

        return $this;
    }
}
