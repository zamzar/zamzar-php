<?php

namespace Zamzar;

class Jobs extends ApiResource
{
    /** Valid API Operations for this Class */
    use \Zamzar\ApiOperations\Paging;
    use \Zamzar\ApiOperations\All;
    use \Zamzar\ApiOperations\Get;

    public function create($params)
    {
        $apiResponse = $this->apiRequest($this->getEndpoint(), 'POST', $params);

        $data = $apiResponse->getBody();

        return new \Zamzar\Job($this->getConfig(), $data);
    }
}
