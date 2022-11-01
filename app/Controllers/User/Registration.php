<?php

namespace App\Controllers\User;
use App\Controllers\BaseController;

class Registration extends BaseController
{
    public function login(){
        $data = [
            'title' => "Log in"
        ];
        
        return view('templates/header', $data) .
            view('user/login') .
            view('templates/footer');
    }
}
