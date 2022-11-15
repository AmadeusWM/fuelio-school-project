<?php

namespace App\Models;

use App\Models\AssetModels\ProductImageModel;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'product';

    protected $allowedFields = [
        'id',
        'user_id',
        'name',
        'price',
        'description',
        'origin',
        'quantity',
        'product_category_id',
    ];

    protected $primaryKey = 'id';

    public function getProductsFromUser($userId){
        $products = $this->where("user_id", $userId)->get()->getResultArray();

        $productsData = array();

        foreach ($products as $product) {
            array_push($productsData, $this->getProductData($product));
        }

        return $productsData;
    }

    public function getProductDataById($id){
        $product = $this->find($id);
        return $this->getProductData($product);
    }

    private function getProductData($product){
        $productCategoryModel = new ProductCategoryModel();
        $productImageModel = new ProductImageModel();
        $images = $productImageModel->where('product_id', $product['id'])->get()->getResultArray();
        $productCategory = $productCategoryModel->find($product['product_category_id']);

        $toImageName = function ($n) {
            return $n['image_name'];
        };

        $imageNames = array_map($toImageName, $images);

        $productData = [
            'id'                  => $product['id'],
            'name'                  => $product['name'],
            'price'                 => $product['price'],
            'description'           => $product['description'],
            'origin'                => $product['origin'],
            'quantity'              => $product['quantity'],
            'product_category'      => $productCategory['name'],
            'images'                => array_values($imageNames)
        ];

        return $productData;
    }

}
