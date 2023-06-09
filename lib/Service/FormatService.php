<?php

namespace Zamzar\Service;

use Zamzar\Collection;
use Zamzar\Format;

class FormatService extends AbstractService
{
    public function get($id)
    {
        $response = $this->client->request('GET', Format::resourceUrl($id));

        return Format::constructFrom($response->getBody(), $this->client->getConfig());
    }

    /**
     * @return \Zamzar\Collection<\Zamzar\Format>
     */
    public function all($params = [])
    {
        $response = $this->client->request('GET', Format::classUrl(), $params);

        return Collection::constructFrom($response->getBody(), $this->client->getConfig(), Format::classUrl(), Format::class);
    }
}
