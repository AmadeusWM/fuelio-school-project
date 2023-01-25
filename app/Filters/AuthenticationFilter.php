<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthenticationFilter implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null){
        if (!session("isLoggedIn")){
            return redirect()->to(base_url("/login"));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

    }
}