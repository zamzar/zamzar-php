<?php

namespace Zamzar;

/**
 * Jobs Object
 */
class Jobs extends ApiResource
{
    /** Valid API Operations for this Class */
    use \Zamzar\ApiOperations\Paging;
    use \Zamzar\ApiOperations\All;
    use \Zamzar\ApiOperations\Get;
    use \Zamzar\ApiOperations\Submit;

    /**
     * Inialises a new instance of the Jobs object
     */
    public function __construct($config)
    {
        $this->apiInit($config);
    }
}
