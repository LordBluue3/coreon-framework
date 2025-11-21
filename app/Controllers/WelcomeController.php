<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class WelcomeController extends BaseController
{
    public function index()
    {
        $data = ['name' => 'Coreon Framework'];
        $this->view('pages.welcome', $data);
    }
}