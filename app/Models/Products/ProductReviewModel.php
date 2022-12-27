<?php

namespace App\Models\Products;

use CodeIgniter\Model;

class ProductReviewModel extends Model
{
    protected $table = 'product_review';

    protected $allowedFields = [
        "id",
	    "user_id",
	    "product_id",
	    "rating",
        "title",
        "content",
    ];

    protected $primaryKey = 'id';

    public function getByProductId($productId){
        return $this->where("product_id", $productId)->get()->getResultArray();
    }
}
