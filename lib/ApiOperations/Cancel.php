<?php

namespace Zamzar\ApiOperations;

/**
 * Cancel Trait
 *
 * Cancels an operation using HTTP DELETE
 *
 * Used on Job Object, returns a Job Object
 *
 * No Params. $this->endpoint is effectively called with the DELETE HTTP method
 *
 * Usage:
 *
 *      $job->cancel();
 *
 *
 */
trait Cancel
{
    public function cancel()
    {
        $this->apiRequest($this->getEndpoint(), 'DELETE');
        $this->refresh();
        return $this;
    }
}
