<?php

namespace App\Controllers;

use App\Controllers\Store\ProductSearchController;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;

class Home extends BaseController
{
    public function index()
    {
        $productSearchController = new ProductSearchController();
        return $productSearchController->index();
    }

    public function successPage()
    {
        $data["title"] = ucfirst("Success");
        return view("templates/header", $data) .
            view("templates/feedback/success") .
            view("templates/footer");
    }

    public function failurePage()
    {
        $data["title"] = ucfirst("Failure");
        return view("templates/header", $data) .
            view("templates/feedback/failure") .
            view("templates/footer");
    }
}
