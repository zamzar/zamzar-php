<?php

Namespace Zamzar;

/**
 * Files Object
 */

class Files extends ApiResource
{

    /** Valid API Operations for this Class */
    use \Zamzar\ApiOperations\Paging;
    use \Zamzar\ApiOperations\All;
    use \Zamzar\ApiOperations\Get;
    use \Zamzar\ApiOperations\Upload;

    /**
	  * Inialises a new instance of the Files object
	  */
    public function __construct($config) 
    {
      $this->apiInit($config);
    }
    
}