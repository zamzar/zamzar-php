<?php

// delete a file

// initialise
$config = include('../config.php');

// autoload
require_once($config['autoload']);

// get the file id
$url = $_GET['url'];
$filename = $_GET['filename'] ?? '';

// only proceed if we have a file id
if (!is_null($url)) {
    // register a new zamzar client
    $zamzar = new \Zamzar\ZamzarClient($config['api-key']);

    // start the import
    $status = "ok";
    $data = ["id" => 0];
    $params = [
        'url' => $url,
    ];
    if ($filename !== '') {
        $params['filename'] = $filename;
    }
    try {
        $import = $zamzar->imports->start($params);
        $data = ["id" => $import->getId()];
    } catch (\Zamzar\Exception\ApiErrorException $e) {
        $data = ["id" => -1];
    }

    // return status
    echo json_encode($data);
}
