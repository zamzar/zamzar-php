<?php

namespace Zamzar\ApiOperations;

use Zamzar\Zamzar;

/**
 * All Trait
 *
 * Returns an array of objects based on the supplied request options
 * Used by Files, Formats, Imports, Jobs objects
 *
 * $requestOptions = [
 *
 *      'limit' => (integer),           // limit the results upto max of 50 records
 *      'before' => (integer),          // retrieve results before this record id
 *      'after' => (integer)            // retrieve results after this record id
 *      'job_status' => 'successful'    // no other statii can be filtered
 *
 *  ]
 *
 * Usage:
 *
 *      $jobs = $zamzar->jobs->all([
 *                  'limit' => 10,
 *                  'after' => 123456,
 *                  'job_status' => 'successful',
 *              ])
 *
 */
trait All
{
    public function all($requestOptions = null)
    {
        // Get the standard format endpoint and modify with Request Options if supplied
        $endpoint = $this->getEndpoint();

        // Capture the request options
        $this->requestOptions = $requestOptions;

        // Separate job status filter as it isn't a query string parameter
        if (static::class == 'Zamzar\Jobs') {
            if (!$requestOptions == null) {
                if (array_search('job_status', array_keys($requestOptions)) !== false) {
                    if ($requestOptions['job_status'] == 'successful') {
                        $endpoint = $endpoint . '/successful';
                        $this->setNewEndPoint($endpoint);
                    }
                    unset($requestOptions['job_status']);
                    $this->requestOptions = $requestOptions;
                }
            }
        }

        // Apppend query parameters if supplied
        if (!$requestOptions == null) {
            $endpoint = $endpoint . '/?' . http_build_query($requestOptions);
        }

        // Make the api request via the ApiResource:apiRequest function
        $apiResponse = $this->apiRequest($endpoint);

        // Get the data from the response
        $data = $apiResponse->getData();

        // Convert to a array of specific objects
        $this->resetData();
        foreach ($data as $object) {
            $objectType = Zamzar::COLLECTION_CLASS_MAP[static::class];
            $this->addData(new $objectType($this->getConfig(), $object));
        }

        // return the object
        return $this;
    }
}
