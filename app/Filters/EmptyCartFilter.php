<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class EmptyCartFilter implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null){
        if (count(session("cart")) == 0){
            return redirect()->to(base_url("/cart/cart"));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

    }
}