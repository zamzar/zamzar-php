<?php

namespace Zamzar\ApiOperations;

/**
 * Refresh Trait
 *
 * Refreshes the values for this object by calling its own endpoint
 *
 * Used on objects which transition state, e.g. Jobs and Imports
 *
 * Usage:
 *
 *      $job->refresh();
 *      $import->refresh();
 *
 */
trait Refresh
{
    public function refresh()
    {
        // Make the api request and return a response
        $apiResponse = $this->apiRequest($this->getEndPoint());

        // Get the Data
        $data = $apiResponse->getBody();

        // Repopulate the object
        $this->setValues($data);

        // Return the object
        return $this;
    }
}
