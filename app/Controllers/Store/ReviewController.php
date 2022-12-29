<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\Orders\OrderModel;
use App\Models\Orders\OrderProductModel;
use App\Models\Products\ProductModel;
use App\Models\Products\ProductCategoryModel;
use App\Models\Products\ProductReviewModel;

class ReviewController extends BaseController
{
    public function addReview($productId)
    {
        $productReviewModel = new ProductReviewModel();
        $productModel = new productModel();

        $product = $productModel->find($productId);

        if (!$this->validateReviewAddition($productId)){
            return redirect()->to(base_url("/failure"));
        }
        
        $session = session();
        
        $data = [
            "user_id"       => $session->get("id"),
            "product_id"    => $product["id"],
            "title"         => $this->request->getVar("title"),
            "content"       => $this->request->getVar("content"),
            "rating"        => $this->request->getVar("rating"),
        ];

        $productReviewModel->save($data);
        $session->setFlashdata('message', "Review added successfully!");
        return redirect()->to(base_url("/success"));
    }

    private function validateReviewAddition($productId){
        $productReviewModel = new ProductReviewModel();
        $orderProductModel = new OrderProductModel();
        $productModel = new productModel();
        
        $session = session();

        $product = $productModel->find($productId);
        
        // product doesn't exist
        if (!isset($product)) {
            $errorMessage = "<ul><li>Not a valid product</li></ul>";
            $session->setFlashdata('errors', $errorMessage);
            return false;
        }

        // valid user
        if (!$session->has("id")) {
            $errorMessage = "<ul><li>You need an account to add reviews.</li></ul>";
            $session->setFlashdata('errors', $errorMessage);
            return false;
        }

        $reviewProductUser = $productReviewModel->where("user_id", $session->get("id"))
            ->where("product_id", $product["id"])
            ->first();
        
        // user has already added a review
        if (isset($reviewProductUser)) {
            $errorMessage = "<ul><li>You have already uploaded a review on this product.</li></ul>";
            $session->setFlashdata('errors', $errorMessage);
            return false;
        }

        $orderByUser = $orderProductModel->where("product_id", $productId)
                            ->join("order", "order_product.order_id = order.id")
                            ->where("user_id", $session->get("id"))
                            ->first();

        // user hasn't bought the product
        if (!isset($orderByUser)) {
            $errorMessage = "<ul><li>You haven't ordered this product before.</li></ul>";
            $session->setFlashdata('errors', $errorMessage);
            return false;
        }

        $rules = [
            "title" => "required|min_length[1]|max_length[128]",
            "content" => "required|min_length[1]|max_length[1024]",
            "rating" => "required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]"
        ];

        if (!$this->validate($rules)) {
            $errorMessage = $this->validator->listErrors();
            $session->setFlashdata('errors', $errorMessage);
            return false;
        }
        
        return true;
    }

    public function deleteReview($reviewId)
    {
        $productReviewModel = new ProductReviewModel();

        $review = $productReviewModel->find($reviewId);

        if (isset($review) && $review["user_id"] == session()->get("id")){
            $productReviewModel->delete($reviewId);
            session()->setFlashdata('message', "Review removed successfully!");
            return redirect()->to(base_url("/success"));
        }
        return redirect()->to(base_url("/failure"));
    }
}
