<?php

namespace Core\Classes;

class View
{
    static function view(string $path)
    {
        $test = __DIR__.''.$path.'.php';
        dd($test);
        return '';
    }
}