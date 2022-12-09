<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;
use App\Models\UserModel;

class WebShopController extends BaseController
{
    public function index($id)
    {
        $userModel = new UserModel();
        $productModel = new ProductModel();
        // $product = $productModel->getProductsByWebshop($id);
        // $data["product"] = $product;
        $user = $userModel->find($id);
        $data["title"] = $user["webshop_name"];
        return view("templates/header", $data) .
            view("user/webshop") .
            view("templates/footer");
    }
}
