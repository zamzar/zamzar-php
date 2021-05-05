<?php

Namespace Zamzar\ApiOperations;

/**
 * Start Trait
 * 
 * Starts an Import
 * 
 * Used on Imports Object, returns an Import Object
 * 
 * $params = [
 *      'url' => (string),          // http(s) (s)ftp s3
 *      'filename' => (string)      // overrides any filename in the url
 * 	]
 * 	
 * Usage: 
 * 
 * 		$import = $zamzar->imports->start([
 * 			'url' => 'https://www.zamzar.com/images/zamzar-logo.png',
 *          'filename' => 'zamzar-logo-renamed.png'
 * 		]);
 * 
 */
trait Start
{
    public function start($params) 
    {

        // Submit the request to start the import
        $apiResponse = $this->apiRequest($this->getEndpoint(), 'POST', $params);

		// Get the response data
		$data = $apiResponse->getBody();

		// Create an import object
		$import = new \Zamzar\Import($this->getConfig(), $data);
		
        // Return import object
		return $import;

    }
}