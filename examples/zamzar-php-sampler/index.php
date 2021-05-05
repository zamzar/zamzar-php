<?php

/**
 * Page level variables are declared in /partials/head.php and prefixed with an underscore to indicate page level vars
 *  $_pf = shorthand for project folder
 *  $_apiKey = the api key which is supplied using a query string to each page
 *  $_environment = the API environment being used (Production or Sandbox (default))
 *  $_zamzar = the ZamzarClient used to make all requests
 */

// config file
$config = include('config.php');

// autoload
require_once($config['autoload']);

// show the home page
require 'views/home/index.html';

