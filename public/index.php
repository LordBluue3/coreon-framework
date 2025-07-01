<?php

require "../boostrap.php";

use Core\Controller;

try{
    $controller = new Controller;

    $controller = $controller->load();
    dd($controller);
}catch(\Exception $e){
    dd($e->getMessage());
}
