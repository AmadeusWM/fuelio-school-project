<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
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

        $session = session();

        if (!isset($product)) {
            $errorMessage = "<ul><li>Not a valid product</li></ul>";
            $session->setFlashdata('errors', $errorMessage);
            return redirect()->to(base_url("/failure"));
        }

        if (!$session->has("id")) {
            $errorMessage = "<ul><li>You have to be logged in, if you want to place a review.</li></ul>";
            $session->setFlashdata('errors', $errorMessage);
            return redirect()->to(base_url("/failure"));
        }

        $reviewsProductUser = $productReviewModel->where("user_id", $session->get("id"))
            ->where("product_id", $product["id"])
            ->get()->getResultArray();

        if (!empty($reviewsProductUser)) {
            $errorMessage = "<ul><li>You have already uploaded a review on this product.</li></ul>";
            $session->setFlashdata('errors', $errorMessage);
            return redirect()->to(base_url("/failure"));
        }

        $rules = [
            "title" => "required|min_length[1]|max_length[128]",
            "content" => "required|min_length[1]|max_length[1024]",
            "rating" => "required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]"
        ];

        if (!$this->validate($rules)) {
            $errorMessage = $this->validator->listErrors();
            $session->setFlashdata('errors', $errorMessage);
            return redirect()->to(base_url("/failure"));
        }
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
