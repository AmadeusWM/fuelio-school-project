<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;

class OverviewController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => ucfirst("Your Account"),
            'page' => "user/account/account"
        ];

        return view('templates/header', $data) .
            view('user/account/overview', $data) .
            view('templates/footer');
    }

    public function getTab($page = "account"){
        return $this->response->setJSON(["view" => view("user/account/" . $page)]);
    }
}