<?php

Namespace Zamzar;

/**
 * Imports Object
 */
class Imports extends ApiResource
{

    /** Valid API Operations for this Class */
    use \Zamzar\ApiOperations\Paging;
    use \Zamzar\ApiOperations\All;
    use \Zamzar\ApiOperations\Get;
    use \Zamzar\ApiOperations\Start;

    /**
	 * Inialises a new instance of the Imports Class
	 */
	public function __construct($config) 
    {
        $this->apiInit($config);
	}
   
}