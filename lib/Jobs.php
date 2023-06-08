<?php

namespace Zamzar;

class Jobs extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;

    public function create($params)
    {
        $response = $this->apiRequest(Job::classUrl(), 'POST', $params);

        return Job::constructFrom($response->getBody(), $this->config);
    }

    public function get($id)
    {
        $response = $this->apiRequest(Job::resourceUrl($id), 'GET');

        return Job::constructFrom($response->getBody(), $this->config);
    }

    public function all($params = [])
    {
        $response = $this->apiRequest(Job::classUrl(), 'GET', $params);

        $this->resetData();
        foreach ($response->getData() as $object) {
            $this->addData(Job::constructFrom($object, $this->config));
        }

        return $this;
    }
}
