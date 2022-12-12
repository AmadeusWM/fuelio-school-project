<?php

namespace App\Controllers\Cart;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\UserModel;


class OrderController extends BaseController
{
    public function index()
    {
    }

    public function addProductToCart()
    {
        // csrf token for ajax
        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();

        $productModel = new ProductModel();

        $quantity = $this->request->getVar("quantity");
        $id = $this->request->getVar("id");
        $product = $productModel->find($id);
        $rules = [
            'quantity'             => 'min_length[1]|max_length[9999]',
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
        } 
        else {
            $cart[$id] = $quantity;
        }
        $session->set("cart", $cart);
        $data["success"] = true;
        return $this->response->setJSON($data);
    }

    public function removeProductFromCart()
    {
        // csrf token for ajax
        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();

        $id = $this->request->getVar("id");

        $session = session();
        $cart = $this->getCart();
        if (array_key_exists($id, $cart)) {
            unset($cart[$id]);
            $session->set("cart", $cart);
            $data["success"] = true;
        }
        else{
            $data["cart"] = $cart ;
            $data["success"] = false;
        }
        return $this->response->setJSON($data);
    }

    public function cartPage()
    {
        $productModel = new ProductModel();

        $data["title"] = "Your Shopping Cart";

        $cartProducts = $this->getCart();

        $productsArr = $productModel->getProductsByIds(array_keys($cartProducts));

        // map products by id, with the quantity included
        $products = [];
        foreach ($productsArr as $product) {
            $id = $product["id"];
            $product["orderQuantity"] = $cartProducts[$id];
            $products[$id] = $product;
        }

        $data["products"] = $products;

        return view("templates/header", $data) .
            view("cart/cart") .
            view("templates/footer");
    }

    public function checkoutPage(){
        $data["title"] = ucfirst("checkout");
        return view("templates/header", $data) .
            view("cart/checkout") .
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
}
