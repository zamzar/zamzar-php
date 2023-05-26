<?php

// get the file content for a given id and download it to the downloads folder

// initialise
$config = include('../config.php');

// autoload
require_once($config['autoload']);

// get the file id
$fileid = $_GET['fileid'];

// only proceed if we have a file id
if (!is_null($fileid)) {
    // register a new zamzar client
    $zamzar = new \Zamzar\ZamzarClient($config['api-key']);

    // get the file
    $file = $zamzar->files->get($fileid);

    // get the path from the config file
    $downloadpath = $config['download-folder'];

    // download the content
    $file->download([
        'download_path' => $downloadpath
    ]);

    // full file name
    $data = ["filename" => $file->getName()];
    echo json_encode($data);
}
