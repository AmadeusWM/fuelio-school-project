<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data['title'] = ucfirst('Home');
        return view('templates/header', $data).
            view('home/homepage') .
            view('templates/footer');
    }
}
