<?php

namespace Zamzar\ApiOperations;

use Zamzar\Zamzar;

/**
 * The Paging trait is used by objects which provide paged collections, for example the Jobs, Files, Formats & Imports endpoints.
 *
 * Whenever a call is made to those endpoints, the paging element returned from the API is stored in the $this->lastresponse variable.
 *
 * Page Navigation functions are provided to traverse backwards and forwards one page at a time.
 *
 *   Example:
 *
 *      $zamzar = new \Zamzar\ZamzarClient('abcd1234');
 *      $jobs = $zamzar->jobs->all(['limit' => 10]);
 *      $jobs = $zamzar->jobs->nextPage();
 *      $jobs = $zamzar->jobs->previousPage();
 */
trait Paging
{
    /**
     * Common page navigation function to use navigate forwards or back
     */
    private function pageNav($direction)
    {
        // Get the base endpoint for jobs which may also include a filter on job status from the previous call
        $endpoint = $this->getEndPoint();

        // Paging data will be stored in the last response
        if ($direction == 'forward') {
            $endpoint = $endpoint . '/?after=' . $this->getLastResponse()->getPaging()->last;
        } elseif ($direction = 'back') {
            $endpoint = $endpoint . '/?before=' . $this->getLastResponse()->getPaging()->first;
        }

        // Maintain any record limits which have previously been set either from the first call to the endpoint or explicitly by the user
        $endpoint = $endpoint . '&limit=' . $this->getLastResponse()->getPaging()->limit;

        // Make the api request via the ApiResource:apiRequest function
        $apiResponse = $this->apiRequest($endpoint);

        // Get the data and paging arrays
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

    /**
     * Next Page
     */
    public function nextPage()
    {
        return $this->pageNav("forward");
    }

    /**
     * Previous Page
     */
    public function previousPage()
    {
        return $this->pageNav("back");
    }
}
