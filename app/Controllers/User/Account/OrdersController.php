<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Controllers\User\Account\OverviewController;
use App\Models\AssetModels\ProductFileModel;
use App\Models\Orders\OrderModel;
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
            $orderModel->orderCanceled($id);

            $session->setFlashdata('message', "Order canceled successfully.");
            return redirect()->to(base_url("/success"));
        } catch (Exception $e) {
            $message = $e->getMessage();
            $session->setFlashdata('errors', "<ul><li>$message</ul></li>");
            return redirect()->to(base_url("/failure"));
        }
    }
}
