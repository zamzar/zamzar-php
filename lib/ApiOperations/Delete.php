<?php

namespace Zamzar\ApiOperations;

/**
 * Delete Trait
 *
 * Deletes a resource using HTTP DELETE
 *
 * Used on File Object, returns a File Object
 *
 * No Params. $this->endpoint is effectively called with the DELETE HTTP method
 *
 * Usage:
 *
 *      $file->delete();
 *
 *
 */
trait Delete
{
    public function delete()
    {
        $this->apiRequest($this->getEndpoint(), 'DELETE');
        return $this;
    }
}
