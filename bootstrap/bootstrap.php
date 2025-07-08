<?php

use Core\Router;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/app/Helpers/helper.php';
require_once dirname(__DIR__) . '/routes/api.php';

if (isset($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'])) {
// start router 
Router::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
}