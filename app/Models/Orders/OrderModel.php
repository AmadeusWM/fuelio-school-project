<?php

namespace App\Models\Orders;

use App\Models\UserModel;
use App\Models\ProductModel;
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
    public function createOrder($data){
        try{
            $userModel = new UserModel();
            $userId = session()->get("id");

            $user = null;
            if ($userId)
                $user = $userModel->find($userId);
            
            if (isset($user)){
                $data["user_id"] = $userId;
                
                $orderId = $this->insert($data);
                
                return $orderId;
            }
            else{
                throw new Exception("Not logged in.");
            }
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function getOrdersByUser($userId){
        
        $orders = $this->where("user_id", $userId)->get()->getResultArray();
        foreach ($orders as $key => $order){
            $orderProducts = $this->getOrderProducts($order);
            $orders[$key]["order_products"] = $orderProducts;
        }
        return $orders;
    }

    private function getOrderProducts($order){
        $orderProductModel = new OrderProductModel();
        $productModel = new ProductModel();

        $query = $orderProductModel->where("order_id", $order["id"]);
        $query = $query->select("order_product.*, product.*, order_product.quantity AS order_quantity")
            ->join("product", "order_product.product_id = product.id", "left");
        $orderProducts = $query->get()->getResultArray();

        $orderProducts = $productModel->insertAllDataIntoProducts($orderProducts);
        
        return $orderProducts;
    }
}
