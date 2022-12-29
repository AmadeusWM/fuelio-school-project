<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\Products\ProductModel;
use App\Models\Products\ProductCategoryModel;

class ProductSearchController extends BaseController
{
    private $fetch_amount = 8;

    public function index($page = 0)
    {
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();

        helper(["form"]);

        $data["title"] = ucfirst("Home");
        $data["page"] = $page;
        $data["amountPages"] = $productModel->countAmountPagesAll($this->fetch_amount);
        $data["products"] = $productModel->getProducts($page, $this->fetch_amount);

        $productCategories = $productCategoryModel->findAll();

        $data["product_categories"] = $productCategories;

        $productsList = view("product/productsList", $data);
        $data["products_list"] = $productsList;

        return $this->page(["home/home", "product/productSearch"], $data);
    }

    public function search($page = 0)
    {
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();

        $filter = $this->request->getGET();

        $products = $productModel->getProductsFiltered($filter, $page, $this->fetch_amount);

        $productCategories = $productCategoryModel->findAll();
        $productsListData["products"] = $products;
        $productsListData["page"] = $page;
        $productsListData["amountPages"] = $productModel->countAmountPages($filter, $this->fetch_amount);

        $productsList = view("product/productsList", $productsListData);

        $data["title"] = ucfirst("Home");
        $data["products_list"] = $productsList;
        $data["product_categories"] = $productCategories;
        $data["filter"] = $filter;

        return $this->page("product/productSearch", $data);
    }
}
