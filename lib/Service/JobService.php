<?php

namespace Zamzar\Service;

use Zamzar\Collection;
use Zamzar\Job;

class JobService extends AbstractService
{
    public function create($params)
    {
        // if params['options'] is an array, JSON encode it
        if (array_key_exists('options', $params) && is_array($params['options'])) {
            $params['options'] = json_encode($params['options']);
        }

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
        $uri = Job::classUrl();

        // Filtering by status doesn't use a querystring, and only supports successful jobs
        if (array_key_exists('status', $params) && $params['status'] === Job::STATUS_SUCCESSFUL) {
            $uri .= '/successful';
            unset($params['status']);
        }

        $response = $this->client->request('GET', $uri, $params);

        return Collection::constructFrom($response->getBody(), $this->client->getConfig(), $uri, Job::class);
    }
}
