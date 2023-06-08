<?php

namespace Zamzar;

class Files extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

    public function create($params)
    {
        $response = $this->apiRequest(File::classUrl(), 'POST', $params);

        return File::constructFrom($response->getBody(), $this->config);
    }

    public function get($id)
    {
        $response = $this->apiRequest(File::resourceUrl($id), 'GET');

        return File::constructFrom($response->getBody(), $this->config);
    }

    public function all($params = [])
    {
        $response = $this->apiRequest(File::classUrl(), 'GET', $params);

        $this->resetData();
        foreach ($response->getData() as $object) {
            $this->addData(File::constructFrom($object, $this->config));
        }

        return $this;
    }
}
