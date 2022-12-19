<?php

namespace App\Models\Orders;

use App\Models\UserModel;
use App\Models\AssetModels\ProductFileModel;
use CodeIgniter\Model;
use Exception;

class OrderModel extends Model
{
    protected $table = 'order';

    protected $allowedFields = [
        "id",
        "user_id",
        "delivery_option",
        "pickup_location",
        "delivery_date",
        "status",
        "first_name",
        "last_name",
        "street",
        "house_number",
        "postal_code",
        "city",
        "country",
    ];

    protected $primaryKey = 'id';

    /**
     * @param $data : 
     *      "delivery_option"
     *      "first_name"
     *      "last_name"
     *      "street"
     *      "house_number"
     *      "postal_code"
     *      "city"
     *      "country"
     */
    public function createOrder($data)
    {
        try {
            $userModel = new UserModel();
            $userId = session()->get("id");

            $user = null;
            if ($userId)
                $user = $userModel->find($userId);

            if (isset($user)) {
                $data["user_id"] = $userId;

                $orderId = $this->insert($data);

                return $orderId;
            } else {
                throw new Exception("Not logged in.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOrdersByUser($userId)
    {
        $orders = $this->where("user_id", $userId)
            ->orderBy("id", "desc")
            ->get()->getResultArray();
        foreach ($orders as $key => $order) {
            $orderProducts = $this->getOrderProducts($order);
            $orders[$key]["order_products"] = $orderProducts;
        }
        return $orders;
    }

    private function getOrderProducts($order)
    {
        $orderProductModel = new OrderProductModel();
        $productFileModel = new ProductFileModel();

        $query = $orderProductModel->where("order_id", $order["id"])
            ->select("order_product.*, user.webshop_name")
            ->join("user", "order_product.seller_id = user.id", "left");
        // $query = $query->select("order_product.*, product.*, order_product.quantity AS order_quantity")
        // $orderProducts = $query->get()->getResultArray();

        $orderProducts = $query->get()->getResultArray();

        foreach ($orderProducts as $key => $order_product) {
            $orderProducts[$key]["files"] = $productFileModel->getFilesByUser($order_product["product_id"]);
        }

        return $orderProducts;
    }

    public function orderDelivered($id)
    {
        $this->setOrderStatus($id, "delivered");
    }

    public function orderCanceled($id)
    {
        $this->setOrderStatus($id, "canceled");
    }

    private function setOrderStatus($id, $status)
    {
        $order = $this->find($id);
        if (!isset($order)) {
            throw new Exception("Invalid order Id");
        }
        if (session("id") == $order["user_id"] && $order["status"] == "sent") {
            $order["status"] = $status;
            $this->update($order["id"], $order);
        } else {
            throw new Exception("Unauthenticated");
        }
    }
}
