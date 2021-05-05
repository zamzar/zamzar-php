<?php

Namespace Zamzar\ApiOperations;

/**
 * Upload Trait
 * 
 * Uploads a local file
 * 
 * Used on Files Object, returns a File Object
 * 
 * $params = [
 *      'name' => (string)           // path/to/file
 * 	]
 * 	
 * Usage: 
 * 
 * 		$file = $zamzar->files->upload([
 * 			'name' => 'path/to/file'
 * 		]);
 * 
 */
trait Upload
{
	public function upload($params) 
	{
		
		// Submit the request to start the upload
		$apiResponse = $this->apiRequest($this->getEndpoint(), 'POST', $params);

		// Get the response data
		$data = $apiResponse->getBody();

		// Create a file object
		$file = new \Zamzar\File($this->getConfig(), $data);
		
		// Return file object
		return $file;

	}
}