<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Controllers\User\Account\OverviewController;
use App\Models\UserModel;
use App\Models\Orders\OrderModel;

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
        
        if (isset($userId)){
            $orders = $orderModel->getOrdersByUser($userId);
            $data["orders"] = $orders;
        }
        
        return view("user/account/orders", $data);
    }
}