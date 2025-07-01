<?php

namespace Core;

use App\Classes\Uri;
use App\Controllers\Blog\HomeController;
use App\Exceptions\ControllerNotExistException;

class Controller{

    private string $uri;

    private string $controller;

    private string $namespace;

    private array $folders = [
        'App\Controllers\Blog'
    ];

    public function __construct()
    {
        $this->uri = Uri::uri();
    }

    public function load(): object
    {
        if($this->isHome()) return $this->controllerHome();

        return $this->controllerNotHome();
    }

    private function isHome(): bool
    {
        return ($this->uri === '/');
    }

    private function controllerHome(): object
    {
        if(!$this->controllerExist('HomeController')) return throw new ControllerNotExistException("This controller not exist");

        return $this->instantiateController();
    }

    private function controllerNotHome(): object
    {
        
    }

    private function controllerExist(string $controller): bool
    {
        $controllerExist = false;

        foreach($this->folders as $folder){
            if(class_exists($folder.'\\'.$controller)){
                $controllerExist = true;
                $this->namespace = $folder;
                $this->controller = $controller;

            }
        }
        return $controllerExist;
    }

    private function instantiateController(): object
    {
        $controller = $this->namespace.'\\'.$this->controller;

        return new $controller;
    }
}