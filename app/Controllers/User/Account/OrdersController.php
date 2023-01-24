<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Controllers\User\Account\OverviewController;
use App\Models\AssetModels\ProductFileModel;
use App\Models\Messaging\MessageModel;
use App\Models\Orders\OrderModel;
use App\Models\Orders\OrderProductModel;
use App\Models\Products\ProductModel;
use Exception;

class OrdersController extends BaseController
{
    public function index($data = [])
    {
        $overviewController = new OverviewController();

        $data['title'] = ucfirst("orders");
        $data['page'] = $this->view($data);

        return $overviewController->index($data);
    }

    private function view($data = [])
    {
        $orderModel = new OrderModel();

        $userId = session()->get("id");

        if (isset($userId)) {
            $orders = $orderModel->getOrdersByUser($userId);
            $data["orders"] = $orders;
        }

        return view("user/account/orders", $data);
    }

    public function orderDelivered($id)
    {
        $session = session();
        try {
            $orderModel = new OrderModel();
            $orderModel->orderDelivered($id);
            $this->sendReviewMessage($id);

            $session->setFlashdata('message', "Order set to delived.");
            return redirect()->to(base_url("/success"));
        } catch (Exception $e) {
            $message = $e->getMessage();
            $session->setFlashdata('errors', "<ul><li>$message</ul></li>");
            return redirect()->to(base_url("/failure"));
        }
    }
    public function orderCanceled($id)
    {
        $session = session();
        try {
            $orderModel = new OrderModel();
            $orderProductModel = new OrderProductModel();
            $orderProducts = $orderProductModel->getOrderProducts($id);
            $this->resetQuantityProducts($orderProducts);
            $orderModel->orderCanceled($id);

            $session->setFlashdata('message', "Order canceled successfully.");
            return redirect()->to(base_url("/success"));
        } catch (Exception $e) {
            $message = $e->getMessage();
            $session->setFlashdata('errors', "<ul><li>$message</ul></li>");
            return redirect()->to(base_url("/failure"));
        }
    }

    private function resetQuantityProducts($orderProducts){
        $productModel = new ProductModel();
        foreach($orderProducts as $orderProduct){
            try{
                $idProduct = $orderProduct["product_id"];
                $quantity = $orderProduct["quantity"];
                $productModel->incrementQuantity($idProduct, $quantity);
            }catch(Exception $e){
                // when product was not found
            }
        }
    }

    private function sendReviewMessage($orderId){
        $messageModel = new MessageModel();
        $orderProductModel = new OrderProductModel();

        $orderProducts = $orderProductModel->where("order_id", $orderId)->get()->getResultArray();
        
        foreach($orderProducts as $orderProduct){
            $messageModel->sendMessage($orderProduct["seller_id"], 
                                        session()->get("id"),
                                        "Review",
                                        $orderProduct["name_product"] . " was delivered, would you like to write a review?",
                                        "review",
                                        $orderProduct["product_id"]
                                    );
        }
    }
}
