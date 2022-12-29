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

    /**
     * ge all reviews on a given product,
     *  with the one's from the logged in user on top.
     */
    public function getProductReviews($productId){
        $userId = session()->get("id");
        $query = $this->where("product_id", $productId);
        if (isset($userId)) {
            $query->orderBy("(user_id=$userId)" ,"desc", true);
        }
        
        return $query->get()->getResultArray();
    }
}
