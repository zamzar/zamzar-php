<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $config = include('../config.php');
    
    // autoload
    require_once($config['autoload']);
    
    $apiKey = $config['api-key'];

    // get parameters
    $targetFormat = $_GET['targetformat'];
    $sourceFormat = $_GET['sourceformat'];
    $exportUrl = $_GET['exporturl'];
    $waitForJob = $_GET['waitforjob'];
    
    // register zamzarclient
    $zamzar = new \Zamzar\ZamzarClient($apiKey);
    $jobs = [];

    // loop through files and submit each one
    if (isset($_FILES['files'])) {
  
        $errors = [];
        $path = $_SERVER['DOCUMENT_ROOT'] . '/files/uploads/';
        $all_files = count($_FILES['files']['tmp_name']);

        for ($i = 0; $i < $all_files; $i++) {
            
            $file_name = $_FILES['files']['name'][$i];
            $file_tmp = $_FILES['files']['tmp_name'][$i];
            $file_type = $_FILES['files']['type'][$i];
            $file_size = $_FILES['files']['size'][$i];
            $array = explode('.', $_FILES['files']['name'][$i]);
            $file_ext = end($array);

            $file = $path . $file_name;

            if (empty($errors)) {
                
                // move file into uploads folder
                move_uploaded_file($file_tmp, $file);

                // build params
                $params = [
                    'source_file' => $file,
                    'target_format' => $targetFormat,
                ];

                if($sourceFormat !== '') {
                    $params['source_format'] = $sourceFormat;
                }

                if($exportUrl !== '') {
                    $params['export_url'] = $exportUrl;
                }

                // submit the job
                $newJob = $zamzar->jobs->submit($params);

                // should we wait for completion? (this could also be done on the client side)
                if($waitForJob == 'true') {
                    $newJob = $newJob->waitForCompletion();
                }
                
                // output the job information
                $jobs[] = $zamzar->getLastResponse()->getBody(); 
                // echo json_encode($zamzar->getLastResponse()->getBody());

            }

        }

        echo json_encode($jobs);

    }
}