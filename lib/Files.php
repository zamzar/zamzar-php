<?php

namespace Zamzar;

class Files extends InteractsWithApi
{
    use \Zamzar\ApiOperations\Paging;
    use \Zamzar\ApiOperations\All;
    use \Zamzar\ApiOperations\Get;

    public function create($params)
    {
        $apiResponse = $this->apiRequest($this->getEndpoint(), 'POST', $params);

        $data = $apiResponse->getBody();

        return new \Zamzar\File($this->getConfig(), $data);
    }
}
