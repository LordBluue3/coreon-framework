<?php

namespace App\Controllers\Blog;

use App\Controllers\BaseController;
use App\Models\User;
use Core\Request;

class ContatoController extends BaseController
{
    public function index(Request $request)
    {
        $input =  $request->input('busca');

        $a = (new User)->delete(2);
        if($a){
            dd( 'deletado');
        }else{
            dd( 'nao');
        }
   
    }

    public function enviar()
    {
        echo "oi2";
    }
}