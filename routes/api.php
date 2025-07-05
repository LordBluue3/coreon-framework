<?php

use Core\Router;

Router::get('/', function () {
    echo "Página inicial";
});

Router::get('/contato', ['ContatoController', 'index']);
Router::post('/contato/enviar', ['ContatoController', 'enviar']);