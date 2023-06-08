<?php

namespace Zamzar;

class Formats extends InteractsWithApi
{
    public function get($id)
    {
        $response = $this->apiRequest(Format::resourceUrl($id), 'GET');

        return Format::constructFrom($response->getBody(), $this->config);
    }

    /**
     * @return \Zamzar\Collection<\Zamzar\Format>
     */
    public function all($params = [])
    {
        $response = $this->apiRequest(Format::classUrl(), 'GET', $params);

        return Collection::constructFrom($response->getBody(), $this->config, Format::classUrl(), Format::class);
    }
}
