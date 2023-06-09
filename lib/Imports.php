<?php

namespace Zamzar;

class Imports extends ApiResource
{
    use \Zamzar\ApiOperations\Paging;
    use \Zamzar\ApiOperations\All;
    use \Zamzar\ApiOperations\Get;

    public function create($params)
    {
        $apiResponse = $this->apiRequest($this->getEndpoint(), 'POST', $params);

        $data = $apiResponse->getBody();

        return new \Zamzar\Import($this->getConfig(), $data);
    }
}
