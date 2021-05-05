<?php

    // Set page variables which all pages can access

    // set the path to the project folder   
    $_pf = $config['project-folder'];

    // set the api key if supplied
    $_apiKey = $config['api-key'];
    if($_apiKey == '') {
        echo '<h3 style="padding: 2em; color: rgb(220, 0, 0); font-family: Courier New">> Error: Add an API Key to the config.php file and set the environment to production or sandbox.</h3>';
        die();
    }

    // get the environment variable
    $_environment = $config['environment'];
    
    // register the ZamzarClient
    $_zamzar = new \Zamzar\ZamzarClient([
        'api_key' => $_apiKey,
        'environment' => $_environment,
    ]);

    // default download folder (zamzar->server->client)
    $_downloadFolder = $config['download-folder'];

    // default upload folder (client->server->zamzar)
    $_uploadFolder = $config['upload-folder'];

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zamzar SDK Sampler</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <script src="https://kit.fontawesome.com/0e49d676ae.js" crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>

</head>

<body onload="loader()">