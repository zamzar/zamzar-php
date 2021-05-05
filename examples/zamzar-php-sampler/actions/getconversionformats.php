<?php

// get the file content for a given id and download it to the downloads folder

// initialise
$config = include('../config.php');

// autoload
require_once($config['autoload']);

// get the file id
$sourceFormat = $_GET['sourceformat'];

// only proceed if we have a file id
if(!is_null($sourceFormat)) {

    // register a new zamzar client
    $zamzar = new \Zamzar\ZamzarClient($config['api-key']);

    // get the file
    $validFormats = $zamzar->formats->get($sourceFormat);

    // output the formats
    echo json_encode($zamzar->getLastResponse()->getBody()->targets); 
    
}

