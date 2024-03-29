<?php

namespace App\Models\Products;

use App\Models\AssetModels\ProductFileModel;
use App\Models\UserModel;
use CodeIgniter\Model;
use Exception;

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

    public function countAmountPagesAll($fetch_amount = 100)
    {
        $filteredProducts = $this->findAll();
        return ceil(count($filteredProducts) / $fetch_amount);
    }

    public function countAmountPages($filter, $fetch_amount = 100)
    {
        $filteredProducts = $this->getProductsFiltered($filter, 0, 0);
        return ceil(count($filteredProducts) / $fetch_amount);
    }

    public function getProducts($offset, $fetch_amount = 100)
    {
        $products = $this->limit($fetch_amount, $fetch_amount * $offset)->get()->getResultArray();

        $products = $this->insertAllDataIntoProducts($products);

        return $products;
    }


    public function getProductsFiltered($filter, $offset, $fetch_amount = 100)
    {
        $productCategoryModel = new ProductCategoryModel();

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

        if (isset($product))
            return $this->getProductData($product);
        else {
            return null;
        }
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
     * Decrements the quantity of a product
     */
    public function decrementQuantity($id, $quantity)
    {
        try {
            $product = $this->find($id);
            if (isset($product) && $product["quantity"] >= $quantity) {
                $product["quantity"] -= $quantity;
                $this->save($product);
            } else {;
                throw new Exception("Invalid quantity or product id");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Decrements the quantity of a product
     */
    public function incrementQuantity($id, $quantity)
    {
        $this->decrementQuantity($id, $quantity*(-1));
    }

    /**
     * @param $products the list of products which will be filled with all products information available
     * @return array a list of products with all attributes set (files, product_category)
     */
    public function insertAllDataIntoProducts($products)
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

        $product["product_category"]      = $productCategory["name"];
        $product["webshop_name"]           = $seller["webshop_name"];
        $product["webshop_id"]             = $seller["id"];
        $product["files"]                 = array_values($files);
        return $product;
    }
}
