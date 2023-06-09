<?php

namespace Zamzar\HttpClient;

use Zamzar;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

/**
 * GuzzleClient
 * Acceps a Config Array, an Endpoint, Method (POST, GET etc), params and semaphores to guide the logic
 * Returns an ApiResponse Object containing Raw JSON Body, Code, Headers
 */
class GuzzleClient
{
    /**
     * A single request method accommodates all requests to the API
     */
    public static function request($config, $endpoint, $method = 'GET', $params = [], $hasLocalFile = false, $getFileContent = false)
    {
        // Initialise Guzzle with the Api Key
        $client = new Client([
            'auth' => [$config['api_key'], ''],
        ]);

        // clientParams is an array which will be passed in the request
        //  - For downloading file content, 'sink' will be used to specify where body of response will be saved
        //  - For posting local files to the files and jobs endpoints, a 'multipart' array will be used
        //  - For all other requests, 'form_params' will be set to the method & raw $params passed in to this function
        $clientParams = array();

        // Standard form params will be the default unless overwritten with multipart for the POST of a file
        $clientParams = [
            'form_params' => $params,
        ];

        // If a GET for file content, then add a sink parameter to download the file
        if ($method == 'GET') {
            if ($getFileContent) {
                $clientParams['sink'] = $params['download_path'];
            }
        } elseif ($method == 'POST') {
            // If we have a local file to post, then it's either a job conversion or a file upload
            if ($hasLocalFile) {
                // If we have a name parameter, this is a full path to a file upload.
                // Add a content parameter which will be fopened in subsequent block
                if (array_key_exists("name", $params)) {
                    $params['content'] = $params['name'];
                    $params['name'] = basename($params['name']);
                }

                // Iterate all params and define a multipart array
                // Each Key/value pair becomes one element represented as name=>, contents=> (as guzzle expects in the multipart specification)
                // Paramters named 'source_file' or 'content' refer to full file paths which need to be fopened
                $clientParams = ['multipart'];
                foreach ($params as $key => $value) {
                    $clientParams['multipart'][] = [
                        'name' => $key,
                        'contents' => ($key == 'source_file' || $key == 'content') ? Psr7\Utils::tryFopen($value, 'r') : $value
                    ];
                }
            }
        }

        // Debug param is useful. Set when initialising the ZamzarClient (boolean)
        $clientParams['debug'] = $config['http_debug'];

        // Don't throw Guzzle Errors for 4xx and 5xx responses, the ApiRequestor class will raise custom Zamzar Exceptions
        $clientParams['http_errors'] = false;

        // Set the user-agent which is stored in the config array
        $clientParams['headers'] = [
            'User-Agent' => $config['user_agent'],
        ];

        // Note that redirects are followed automatically with a max of 5 redirects
        // https://docs.guzzlephp.org/en/stable/request-options.html#allow-redirects

        // Make the Request
        $response = $client->request($method, $endpoint, $clientParams);

        // Return the response
        if (!is_null($response)) {
            return new \Zamzar\ApiResponse(
                $response->getBody(),
                $response->getStatusCode(),
                $response->getHeaders(),
            );
        } else {
            // If we are here, then it's a strange place to be
            throw new \Zamzar\Exception\UnknownApiErrorException($config, $endpoint, null, 'An unexpected error has occurred. No response received');
        }
    }
}
