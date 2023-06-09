<?php

namespace Zamzar\Service;

use Zamzar\Collection;
use Zamzar\Job;

class JobService extends AbstractService
{
    public function create($params)
    {
        $response = $this->client->request('POST', Job::classUrl(), $params);

        return Job::constructFrom($response->getBody(), $this->client->getConfig());
    }

    public function get($id)
    {
        $response = $this->client->request('GET', Job::resourceUrl($id));

        return Job::constructFrom($response->getBody(), $this->client->getConfig());
    }

    /**
     * @return \Zamzar\Collection<\Zamzar\Job>
     */
    public function all($params = [])
    {
        $response = $this->client->request('GET', Job::classUrl(), $params);

        return Collection::constructFrom($response->getBody(), $this->client->getConfig(), Job::classUrl(), Job::class);
    }
}
