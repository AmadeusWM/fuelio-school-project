<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\Products\ProductModel;
use App\Models\Products\ProductCategoryModel;
use App\Models\Products\ProductReviewModel;

class ProductController extends BaseController
{
    public function index($id)
    {
        $productModel = new ProductModel();
        $productReviewModel = new ProductReviewModel();
        $product = $productModel->getProductDataById($id);
        if (!isset($product)) {
            session()->setFlashdata("errors", "<ul><li>Product cannot be found.</ul></li>");
            return redirect()->to(base_url("/failure"));
        }
        $data["product"] = $product;
        $data["title"] = $product["name"];
        $data["reviews"] = $productReviewModel->getProductReviews($product["id"]);

        return $this->page("product/product", $data);
    }
}
