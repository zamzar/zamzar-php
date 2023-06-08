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
        $params = [];

        // Paging data will be stored in the last response
        if ($direction == 'forward') {
            $params['after'] = $this->getLastResponse()->getPaging()['last'];
        } elseif ($direction = 'back') {
            $params['before'] = $this->getLastResponse()->getPaging()['first'];
        }

        $params['limit'] = $this->getLastResponse()->getPaging()['limit'];

        $apiResponse = $this->apiRequest($this->getEndpoint(), 'GET', $params);

        $this->resetData();
        foreach ($apiResponse->getData() as $object) {
            $objectType = Zamzar::COLLECTION_CLASS_MAP[static::class];
            $this->addData($objectType::constructFrom($object, $this->getConfig()));
        }

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
