<?php

// delete a file

// initialise
$config = include('../config.php');

// autoload
require_once($config['autoload']);

// get the file id
$fileid = $_GET['fileid'];

// only proceed if we have a file id
if(!is_null($fileid)) {

    // register a new zamzar client
    $zamzar = new \Zamzar\ZamzarClient($config['api-key']);

    // get the file
    $file = $zamzar->files->get($fileid);

    // delete the file
    $status = "ok";
    try {
        $file = $file->delete();
    } catch (\Zamzar\Exception\ApiErrorException $e) {
        $status = "error";
    }

    // return status
    $data = ["status" => $status];
    echo json_encode($data);
    
}

