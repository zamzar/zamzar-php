<?php

namespace Zamzar;

class Jobs extends InteractsWithApi
{
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

    /**
     * @return \Zamzar\Collection<\Zamzar\Job>
     */
    public function all($params = [])
    {
        $response = $this->apiRequest(Job::classUrl(), 'GET', $params);

        return Collection::constructFrom($response->getBody(), $this->config, Job::classUrl(), Job::class);
    }
}
