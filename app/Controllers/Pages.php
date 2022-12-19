<?php

namespace App\Controllers;

use App\Models\Messaging\MessageModel;

class Pages extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function initPage($viewPaths, $data)
    {
        $messageModel = new MessageModel();
        
        $data["notifications"] = $messageModel->getMessagesByUser(session("id"));
        $data["notifications_amount"] = count($data["notifications"]);

        $notificationsView = view("messaging/messages", $data);
        $data["notificationsView"] = $notificationsView;

        $page = view("templates/header", $data);

        foreach ($viewPaths as $path) {
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
