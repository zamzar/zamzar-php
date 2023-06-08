<?php

namespace Zamzar;

class Jobs extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

    public function create($params)
    {
        $apiResponse = $this->apiRequest(Job::classUrl(), 'POST', $params);

        $data = $apiResponse->getBody();

        return Job::constructFrom((array)$data, $this->config);
    }

    public function get($id)
    {
        $apiResponse = $this->apiRequest(Job::resourceUrl($id), 'GET');

        $data = $apiResponse->getBody();

        return Job::constructFrom((array)$data, $this->config);
    }

    public function all($params = [])
    {
        $apiResponse = $this->apiRequest(Job::classUrl(), 'GET', $params);

        $data = $apiResponse->getData();

        $this->resetData();
        foreach ($data as $object) {
            $this->addData(Job::constructFrom((array)$object, $this->config));
        }

        return $this;
    }
}
