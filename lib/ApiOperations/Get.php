<?php

namespace Zamzar\ApiOperations;

use Zamzar\Zamzar;

/**
 * Get Trait
 *
 * Retrieves an individual object
 *
 * Used by Files, Formats, Imports, Jobs
 * Returns an object of the related type
 *
 * $params = [
 *      'id' => (integer) (string) (array)      // Object Id or Name
 *  ]
 *
 * Usage:
 *
 *      $file = $zamzar->files->get(123456)
 *
 */
trait Get
{
    public function get($params)
    {
        // This Get method will have been called on the collection class, get the singular class name
        $className = Zamzar::COLLECTION_CLASS_MAP[static::class];

        // If params is an array, extract the id
        if (is_array($params)) {
            $endpoint = $this->getEndpoint() . '/' . $params['id'];
        } else {
            $endpoint = $this->getEndpoint() . '/' . $params;
        }

        $apiResponse = $this->apiRequest($endpoint);

        $data = $apiResponse->getBody();

        // Return a new initialised object of the appropriate type
        return new $className($this->getConfig(), $data);
    }
}
