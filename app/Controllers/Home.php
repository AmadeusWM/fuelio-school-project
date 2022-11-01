<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data['title'] = ucfirst('Home');
        // add to data a list with the css file locations, such that the header can import the right css files
        return view('templates/header', $data).
            view('home/home') .
            view('templates/footer');
    }
}
