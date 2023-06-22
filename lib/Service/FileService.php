<?php

namespace Zamzar\Service;

use Zamzar\Collection;
use Zamzar\File;

class FileService extends AbstractService
{
    public function create($params)
    {
        $response = $this->client->request('POST', File::classUrl(), $params);

        return File::constructFrom($response->getBody(), $this->client->getConfig());
    }

    public function get($id)
    {
        $response = $this->client->request('GET', File::resourceUrl($id));

        return File::constructFrom($response->getBody(), $this->client->getConfig());
    }

    /**
     * @return \Zamzar\Collection<\Zamzar\File>
     */
    public function all($params = [])
    {
        $response = $this->client->request('GET', File::classUrl(), $params);

        return Collection::constructFrom($response->getBody(), $this->client->getConfig(), File::classUrl(), File::class);
    }
}
