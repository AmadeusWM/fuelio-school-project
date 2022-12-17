<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;

class ProductController extends BaseController
{
    public function index($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductDataById($id);
        if (isset($product)) {
            $data["product"] = $product;
            $data["title"] = $product["name"];
            return view("templates/header", $data) .
                view("product/product") .
                view("templates/footer");
        }
        else{
            session()->setFlashdata("errors", "<ul><li>Product cannot be found.</ul></li>");
            return redirect()->to(base_url("/failure"));
        }
    }
}
