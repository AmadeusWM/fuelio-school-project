<?php

namespace App\Controllers\Cart;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\Orders\PickupLocationModel;
use App\Models\Orders\OrderModel;
use App\Models\Orders\OrderProductModel;
use App\Models\UserModel;
use Exception;


class OrderController extends BaseController
{
    public function index()
    {
    }

    public function addProductToCart()
    {
        // csrf token for ajax
        $data["csrf_value"] = csrf_hash();
        $data["csrf_token"] = csrf_token();

        $productModel = new ProductModel();

        $quantity = $this->request->getVar("quantity");
        $id = $this->request->getVar("id");
        $product = $productModel->find($id);
        $rules = [
            "quantity"             => "min_length[1]|max_length[9999]",
        ];
        if (!($this->validate($rules)
            && isset($product))) {
            $data["success"] = false;
            return $this->response->setJSON($data);
        }

        $session = session();
        $cart = $this->getCart();
        if ($product["quantity"] < $quantity) { // fails when quantity more than in stock
            $data["success"] = false;
            return $this->response->setJSON($data);
        } else {
            $cart[$id] = $quantity;
        }
        $session->set("cart", $cart);
        $data["success"] = true;
        return $this->response->setJSON($data);
    }

    public function removeProductFromCart()
    {
        // csrf token for ajax
        $data["csrf_value"] = csrf_hash();
        $data["csrf_token"] = csrf_token();

        $id = $this->request->getVar("id");

        $session = session();
        $cart = $this->getCart();
        if (array_key_exists($id, $cart)) {
            unset($cart[$id]);
            $session->set("cart", $cart);
            $data["success"] = true;
        } else {
            $data["cart"] = $cart;
            $data["success"] = false;
        }
        return $this->response->setJSON($data);
    }

    public function placeOrder()
    {
        $orderModel = new OrderModel();
        $orderProductModel = new OrderProductModel();

        $delivery_option      = $this->request->getVar("delivery_option");
        $pickup_location_id = $this->request->getVar("pickup_location");

        $cart = $this->getCart();

        $errorMessage = $this->validateOrder();

        if (isset($errorMessage)) {
            $data["errors"] = $errorMessage;
            return $this->failurePage($data);
        }

        $data = [
            "delivery_option"   => $this->request->getVar("delivery_option"),
            "pickup_location"   => $delivery_option == "pickup" ? $pickup_location_id : null,
            "first_name"        => $this->request->getVar("first_name"),
            "last_name"         => $this->request->getVar("last_name"),
            "street"            => $this->request->getVar("street"),
            "house_number"      => $this->request->getVar("house_number"),
            "postal_code"       => $this->request->getVar("postal_code"),
            "city"              => $this->request->getVar("city"),
            "country"           => $this->request->getVar("country"),
            "delivery_date"     => $this->request->getVar("delivery_date"),
        ];

        try {
            $orderId = $orderModel->createOrder($data);

            foreach ($cart as $productId => $quantity) {
                $orderProductModel->addOrderProduct($orderId, $productId, $quantity);
            }

            session()->set("cart", []);

            return redirect()->to(base_url("/cart/success"));
        } catch (Exception $e) {
            $errorMessage = "<ul><li>" . $e->getMessage() . "</li></ul>";
            $data["errors"] = $errorMessage;
            return $this->failurePage($data);
        }
    }

    public function setDelivered(){
        
    }

    public function cartPage()
    {
        $data["title"] = "Your Shopping Cart";

        $data["products"] = $this->getProductsWithQuantity();

        return view("templates/header", $data) .
            view("cart/cart") .
            view("templates/footer");
    }

    public function checkoutPage($data = [])
    {
        $pickupLocationModel = new PickupLocationModel();

        $data["title"] = ucfirst("checkout");
        $data["pickup_locations"] = $pickupLocationModel->findAll();

        $products = $this->getProductsWithQuantity();

        $pricesLambda = function ($a) {
            return $a["price"] * $a["orderQuantity"];
        };

        $data["totalPrice"] = array_sum(array_map($pricesLambda, $products));

        return view("templates/header", $data) .
            view("cart/checkout") .
            view("templates/footer");
    }


    public function successPage()
    {
        $data["title"] = ucfirst("Success");
        return view("templates/header", $data) .
            view("cart/success") .
            view("templates/footer");
    }

    public function failurePage($data = [])
    {
        $data["title"] = ucfirst("Failure");
        return view("templates/header", $data) .
            view("cart/failure") .
            view("templates/footer");
    }

    private function getCart()
    {
        $session = session();
        if (!$session->has("cart")) {
            $session->set("cart", []);
        }
        return $session->get("cart");
    }

    private function validateOrder()
    {
        $pickupLocationModel = new PickupLocationModel();

        $cart = $this->getCart();

        $delivery_option    = $this->request->getVar("delivery_option");
        $pickup_location_id = $this->request->getVar("pickup_location");

        $rules = [
            "delivery_option"       => "required|min_length[1]|max_length[64]|in_list[delivery,pickup]",
            "first_name"            => "required|min_length[1]|max_length[256]",
            "last_name"             => "required|min_length[1]|max_length[256]",
            "street"                => "required|min_length[1]|max_length[256]",
            "house_number"          => "required|numeric|min_length[1]|max_length[50]",
            "postal_code"           => "required|min_length[1]|max_length[50]",
            "city"                  => "required|min_length[1]|max_length[256]",
            "country"               => "required|min_length[1]|max_length[256]",
            "delivery_date"         => "required"
        ];
        if ($delivery_option == "pickup") {
            $rules["pickup_location"] = "required";
        }

        if (!($this->validate($rules))) {
            $errorMessage = $this->validator->listErrors();
            return $errorMessage;
        }

        $pickup = $pickupLocationModel->find($pickup_location_id);
        if (!isset($pickup)) {
            $errorMessage = "<ul><li>Invalid Pickup Location</li></ul>";
            return $errorMessage;
        }

        if (count($cart) <= 0) {
            $errorMessage = "<ul><li>You don't have anything in your cart</li></ul>";
            return $errorMessage;
        }

        $result = $this->validateCart();
        if (!($result["success"])) {
            $product = $result["product"];
            $errorMessage = "<ul><li>" . $product["name"] . " has only " . $product["quantity"] . " left in stock." . " </li></ul>";
            return $errorMessage;
        }
        return null;
    }

    /**
     * @return [false, {product}] if not enough quantity of a product (with the invalid product)
     * @return [true] if enough quantity of all products
     */
    private function validateCart()
    {
        $products = $this->getProductsWithQuantity();
        foreach ($products as $product) {
            if ($product["orderQuantity"] > $product["quantity"])
                return [
                    "success" => false,
                    "product" => $product
                ];
        }
        return ["success" => true];
    }

    private function getProductsWithQuantity()
    {
        $productModel = new ProductModel();

        $cartProducts = $this->getCart();

        $productsArr = $productModel->getProductsByIds(array_keys($cartProducts));

        // map products by id, with the quantity included
        $products = [];
        foreach ($productsArr as $product) {
            $id = $product["id"];
            $product["orderQuantity"] = $cartProducts[$id];
            $products[$id] = $product;
        }

        return $products;
    }
}
