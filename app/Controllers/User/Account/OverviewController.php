<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;

class OverviewController extends BaseController
{
    /**
     * data needs a key
     *  'page' => (view to show)
     *  'title' => (page title)
     */
    public function index($data)
    {
        return view('templates/header', $data) .
            view('user/account/overview', $data) .
            view('templates/footer');
    }
}