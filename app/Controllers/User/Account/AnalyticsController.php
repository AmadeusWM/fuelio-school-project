<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Controllers\User\Account\OverviewController;
use App\Models\Orders\OrderProductModel;
use App\Models\UserModel;

class AnalyticsController extends BaseController
{
    public function index($data = [])
    {
        $overviewController = new OverviewController();

        $data['title'] = ucfirst("analytics");
        $data['page'] = $this->view($data);

        return $overviewController->index($data);
    }

    private function view($data = [])
    {
        $orderProductModel = new OrderProductModel();

        // get all order products of which their order's status is "delivered"
        $orderProducts = $orderProductModel
            ->where("seller_id", session()->get("id"))
            ->select("order_product.*, order.status")
            ->join("order", "order_product.order_id = order.id", "left")
            ->where("status", "delivered")
            ->get()->getResultArray();

        $orderStats = [];

        foreach ($orderProducts as $orderProduct) {
            // map by (name + price):
            //      - multiple products with the same name but different price
            //      - same products, but name changed? 
            $productId = $orderProduct["name_product"] . $orderProduct["price_product"];
            if (array_key_exists($productId, $orderStats)) {
                $orderStats[$productId]["total_sold"] += $orderProduct["quantity"];
            } else {
                $orderStats[$productId] = [
                    "id_product" => $orderProduct["product_id"],
                    "name_product" => $orderProduct["name_product"],
                    "price_product" => $orderProduct["price_product"],
                    "total_sold" => $orderProduct["quantity"]
                ];
            }
        }

        $data["stats"] = $orderStats;

        return view("user/account/analytics", $data);
    }
}
