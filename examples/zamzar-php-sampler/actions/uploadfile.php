<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $config = include('../config.php');

    // autoload
    require_once($config['autoload']);

    $apiKey = $config['api-key'];
    
    // register zamzarclient
    $zamzar = new \Zamzar\ZamzarClient($apiKey);
    $data = [];
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

                // upload to zamzar
                $newFile = $zamzar->files->upload([
                    'name' => $file
                ]);
                $data = ["id" => $newFile->getId()];
            
            }

        }

        //print_r($response);
        echo json_encode($data);

        // if ($errors) print_r($errors);
    }
}