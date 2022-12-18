<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Exception;

class WebShopController extends BaseController
{
    public function index($id)
    {
        try {
            $userModel = new UserModel();

            $user = $userModel->getPublicUserData($id);

            $data["title"] = $user["webshop_name"];
            $data["webshop"] = $user;
            return $this->page("user/webshop", $data);
        } catch (Exception $e) {
            session()->setFlashdata("errors", "<ul><li>Invalid Webshop</li></ul>");
            return redirect()->to(base_url("/failure"));
        }
    }
}
