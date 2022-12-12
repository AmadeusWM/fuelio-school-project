<?php

namespace App\Models;

use App\Models\AssetModels\ProductFileModel;
use App\Models\UserModel;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = "product";

    protected $allowedFields = [
        "id",
        "user_id",
        "name",
        "price",
        "description",
        "origin",
        "quantity",
        "product_category_id",
    ];

    protected $primaryKey = "id";

    public function getProductsFromUser($userId)
    {
        $products = $this->where("user_id", $userId)->get()->getResultArray();

        $productsData = array();

        foreach ($products as $product) {
            array_push($productsData, $this->getProductData($product));
        }

        return $productsData;
    }



    public function getProducts($offset, $fetch_amount = 100)
    {
        if ($fetch_amount > 100)
            $fetch_amount = 100;
        $products = $this->limit($fetch_amount, $fetch_amount * $offset)->get()->getResultArray();

        $products = $this->insertAllDataIntoProducts($products);

        return $products;
    }


    public function getProductsFiltered($filter, $offset, $fetch_amount = 100)
    {
        $productCategoryModel = new ProductCategoryModel();

        if ($fetch_amount > 100)
            $fetch_amount = 100;


        $category_id = null;
        if (isset($filter["category"]) && $filter["category"] != "All")
            $category_id = $productCategoryModel->where('name', $filter["category"])->first()['id'];

        $query = $this;

        if (isset($filter['search_terms']))
            $query = $query->like('name', $filter['search_terms']);
        if (isset($filter['origin']))
            $query = $query->like('origin', $filter['origin']);
        if (isset($filter['max_price']) && $filter['max_price'] > 0)
            $query = $query->where('price <=', $filter['max_price']);
        if (isset($category_id))
            $query = $query->where('product_category_id', $category_id);

        $query = $query->limit($fetch_amount, $fetch_amount * $offset);

        $products = $query->get()->getResultArray();

        $products = $this->insertAllDataIntoProducts($products);

        return $products;
    }

    public function getProductDataById($id)
    {
        $product = $this->find($id);

        return $this->getProductData($product);
    }

    public function getProductsByIds($idList)
    {
        if (empty($idList)) {
            return [];
        }

        $products = $this->whereIn('id', $idList)->get()->getResultArray();

        return $this->insertAllDataIntoProducts($products);
    }

    /**
     * @param $products the list of products which will be filled with all products information available
     * @return products a list of products with all attributes set (files, product_category)
     */
    private function insertAllDataIntoProducts($products)
    {
        $productsData = array();
        foreach ($products as $product) {
            array_push($productsData, $this->getProductData($product));
        }
        return $productsData;
    }

    private function getProductData($product)
    {
        $productCategoryModel = new ProductCategoryModel();
        $userModel = new UserModel();
        $productFileModel = new ProductFileModel();
        $files = $productFileModel->where("product_id", $product["id"])->get()->getResultArray();
        $productCategory = $productCategoryModel->find($product["product_category_id"]);

        $seller = $userModel->find($product["user_id"]);

        $productData = [
            "id"                  => $product["id"],
            "name"                  => $product["name"],
            "price"                 => $product["price"],
            "description"           => $product["description"],
            "origin"                => $product["origin"],
            "quantity"              => $product["quantity"],
            "product_category"      => $productCategory["name"],
            "webshop_name"           => $seller["webshop_name"],
            "webshop_id"             => $seller["id"],
            "files"                 => array_values($files)
        ];
        return $productData;
    }
}
