<?php

namespace Zamzar;

class Files extends InteractsWithApi
{
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

    /**
     * @return \Zamzar\Collection<\Zamzar\File>
     */
    public function all($params = [])
    {
        $response = $this->apiRequest(File::classUrl(), 'GET', $params);

        return Collection::constructFrom($response->getBody(), $this->config, File::classUrl(), File::class);
    }
}
