<?php

use Core\Router;

require "vendor/autoload.php";
require "app/Helpers/helper.php";

// load the routes
require __DIR__ . '/../routes/api.php';

// start router 
Router::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);