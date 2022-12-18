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
            return $this->page("product/product", $data);
        }
        else{
            session()->setFlashdata("errors", "<ul><li>Product cannot be found.</ul></li>");
            return redirect()->to(base_url("/failure"));
        }
    }
}
