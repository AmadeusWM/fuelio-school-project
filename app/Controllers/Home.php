<?php

namespace App\Controllers;
use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        $data['title'] = ucfirst('Home');

        $data['products'] = $productModel->findAll();

        return view('templates/header', $data).
            view('home/home') .
            view('templates/footer');
    }
}
