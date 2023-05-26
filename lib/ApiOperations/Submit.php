<?php

namespace Zamzar\ApiOperations;

/**
 * Submit Trait
 *
 * Submits a job to the api and optionally downloads and deletes files post conversion
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
 *              //optional sdk vars...
 *
 *              // if specified, wait for the job to complete and download the converted files to this folder
 *              download_path => 'path/to/folder',
 *
 *              // the default number of seconds to wait for the job to complete
 *              timeout => 60,
 *
 *              // delete files on zamzar, all files, the source file, or the converted files
 *              delete_files => 'all' | 'source' | 'target',
 *
 * ]
 *
 * Usage:
 *
 *          // submit a local file, wait for completion and delete source and target files
 *          $job = $zamzar->jobs->submit([
 *              'source_file' => 'path/to/file',
 *              'target_format' => 'xxx',
 *              'download_path' => 'path/to/folder',
 *              'timeout' => 120,
 *              'delete_files' => 'all'
 *          ])
 *
 */
trait Submit
{
    public function submit($params)
    {
        // Submit the request (essential parameters will be validated)
        $apiResponse = $this->apiRequest($this->getEndpoint(), 'POST', $params);

        // Get the response data
        $data = $apiResponse->getBody();

        // Create a job object
        $job = new \Zamzar\Job($this->getConfig(), $data);

        // Download converted files?
        if (array_key_exists("download_path", $params)) {
            // Set the timeout
            $timeout = 60;
            if (array_key_exists("timeout", $params)) {
                $timeout = $params["timeout"];
            }

            // Wait
            $job = $job->waitForCompletion(['timeout' => $timeout]);

            // Check the status of the job and throw an error if the job failed
            if ($job->getStatus() == 'successful') {
                // Download converted files
                $job = $job->downloadTargetFiles($params);

                // Delete converted files?
                if (array_key_exists("delete_files", $params)) {
                    switch ($params["delete_files"]) {
                        case 'all':
                            $job = $job->deleteAllFiles();
                            break;
                        case 'source':
                            $job = $job->deleteSourceFile();
                            break;
                        case 'target':
                            $job = $job->deleteTargetFiles();
                            break;
                    }
                }
            }
        }

        // Return the job
        return $job;
    }
}
