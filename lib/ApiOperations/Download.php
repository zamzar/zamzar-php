<?php

namespace Zamzar\ApiOperations;

/**
 * Download Trait
 *
 * Downloads the content of a resource (file)
 *
 * Used on File Object, returns the File Object
 *
 * $params = [
 *      'download_path' => (string)           // path/to/download/folder
 *  ]
 *
 * Usage:
 *
 *     $file->download([
 *         'download_path' => 'path/to/folder'
 *     ]);
 *
 */
trait Download
{
    public function download($params)
    {
        $this->apiRequest($this->getEndpoint(true), 'GET', $params, true, $this->name);
        return $this;
    }
}
