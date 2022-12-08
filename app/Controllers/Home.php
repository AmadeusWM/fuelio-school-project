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
}
