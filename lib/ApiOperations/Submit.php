<?php

namespace Zamzar\ApiOperations;

/**
 * Submit Trait
 *
 * Submits a job to the api
 *
 * Used on Jobs Object, returns a Job Object
 *
 * $params = [
 *
 *              //mandatory api vars
 *              source_file => 'path/to/file' | 'url' | fileid,
 *              target_format => 'xxx',
 *
 *              //optional api vars
 *              source_format => 'xxx',
 *              export_url => 'xxx,
 *
 * ]
 *
 * Usage:
 *
 *          $job = $zamzar->jobs->submit([
 *              'source_file' => 'path/to/file',
 *              'target_format' => 'xxx',
 *          ]);
 *
 */
trait Submit
{
    public function submit($params)
    {
        $apiResponse = $this->apiRequest($this->getEndpoint(), 'POST', $params);

        $data = $apiResponse->getBody();

        return new \Zamzar\Job($this->getConfig(), $data);
    }
}
