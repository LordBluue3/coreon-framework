<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class WelcomeController extends BaseController
{
    public function index()
    {
        $data = ['name' => 'Coren Framework'];
        $this->view('pages.welcome', $data);
    }
}