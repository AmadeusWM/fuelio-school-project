<?php

namespace App\Controllers;

use App\Models\MessageModel;

class Pages extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function initPage($viewPaths, $data)
    {
        $messageModel = new MessageModel();
        $data["notifications"] = $messageModel->where("receiver_id", session("id"))->get()->getResultArray();
        $data["notifications_amount"] = count($data["notifications"]);
        
        $page = view("templates/header", $data);

        foreach ($viewPaths as $path){
            $page = $page . view($path);
        }
        $page = $page . view("templates/footer");

        return $page;
    }

    public function successPage()
    {
        $data["title"] = ucfirst("Success");
        return $this->page("templates/feedback/success", $data);
    }

    public function failurePage()
    {
        $data["title"] = ucfirst("Failure");
        return $this->page("templates/feedback/failure", $data);
    }
}
