<?php

namespace App\Models\Orders;

use CodeIgniter\Model;
use App\Models\Products\ProductModel;
use Exception;

class OrderProductModel extends Model
{
    protected $table = 'order_product';

    protected $allowedFields = [
        "id",
        "order_id",
        "product_id",
        // copy of the product inform
        "seller_id",
        "quantity",
        "origin_product",
        "price_product",
        "name_product"
    ];

    protected $primaryKey = 'id';

    /**
     * @param $orderId : id of the order to which this order product has to be added
     * @param $productId : id of the product this order is containing
     * @param $quantity : quantity of the ordered product
     */
    public function addOrderProduct($orderId, $productId, $quantity)
    {
        try {
            $orderModel = new OrderModel();
            $productModel = new ProductModel();

            $order = $orderModel->find($orderId);
            $product = $productModel->find($productId);

            if (
                isset($order)
                && isset($product)
                && $quantity >= 1
                && $quantity <= $product["quantity"]
            ) {
                $data = [
                    "order_id" => $orderId,
                    "product_id" => $productId,
                    "quantity" => $quantity,
                    "seller_id" => $product["user_id"],
                    "origin_product" => $product["origin"],
                    "price_product" => $product["price"],
                    "name_product" => $product["name"]
                ];
                $this->save($data);
                $productModel->decrementQuantity($productId, $quantity);
            } else {
                throw new Exception("$orderId, $productId, $quantity");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
