<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class Registration extends BaseController
{

    public function registerView()
    {
        $data = [
            'title' => ucfirst("Register")
        ];

        return view('templates/header', $data) .
            view('user/register') .
            view('templates/footer');
    }

    public function loginView()
    {
        $data = [
            'title' => ucfirst("Log in")
        ];

        return view('templates/header', $data) .
            view('user/login') .
            view('templates/footer');
    }

    public function register()
    {
    }

    public function login()
    {
    }
}
