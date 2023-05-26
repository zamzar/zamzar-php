<?php

// cancel a job

// initialise
$config = include('../config.php');

// autoload
require_once($config['autoload']);

// get the file id
$jobid = $_GET['jobid'];

// only proceed if we have a file id
if (!is_null($jobid)) {
    // register a new zamzar client
    $zamzar = new \Zamzar\ZamzarClient($config['api-key']);

    // get the job
    $job = $zamzar->jobs->get($jobid);

    // cancel the job
    $status = "ok";
    try {
        $job = $job->cancel();
    } catch (\Zamzar\Exception\ApiErrorException $e) {
        $status = "error";
    }

    // return status
    $data = ["status" => $status];
    echo json_encode($data);
}
