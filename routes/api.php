<?php

use App\Controllers\Blog\ContatoController;
use Core\Router;

Router::get('/', function () {
    echo "Página inicial";
});

Router::get('/contato', [ContatoController::class, 'index']);
Router::get('/contato/enviar', [ContatoController::class, 'enviar']);