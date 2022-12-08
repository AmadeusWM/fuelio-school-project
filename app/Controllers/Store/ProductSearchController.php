<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;

class ProductSearchController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();

        helper(["form"]);

        $data["title"] = ucfirst("Home");
        $data["products"] = $productModel->getProducts(0);

        $productCategories = $productCategoryModel->findAll();

        $data["product_categories"] = $productCategories;

        $productsList = view("product/productsList", $data);
        $data["products_list"] = $productsList;
        
        return view("templates/header", $data) .
            view("home/home") .
            view("product/productSearch") .
            view("templates/footer");
    }

    public function search(){
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();
        
        $filter = $this->request->getGET();
        
        $data["title"] = ucfirst("Home");

        $products = $productModel->getProductsFiltered($filter, 0);

        $productCategories = $productCategoryModel->findAll();

        $data["product_categories"] = $productCategories;

        $data["products"] = $products;

        $productsList = view("product/productsList", $data);
        $data["products_list"] = $productsList;
        
        return view("templates/header", $data) .
            view("product/productSearch") .
            view("templates/footer");
    }
}
