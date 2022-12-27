<?php

namespace App\Controllers;

use App\Controllers\Store\ProductSearchController;
use App\Models\Products\ProductModel;
use App\Models\Products\ProductCategoryModel;

class Home extends BaseController
{
    public function index()
    {
        $productSearchController = new ProductSearchController();
        return $productSearchController->index();
    }
}
