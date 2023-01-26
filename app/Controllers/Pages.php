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
        $lambda = function ($e){return $e["read"] == false;};
        $unreadMessages = array_filter($data["notifications"], $lambda);
        $data["notifications_amount"] = count($unreadMessages);

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

    public function AccessibilityPage(){
        $data["title"] = ucfirst("Accessibility");
        return $this->page("additional/Accessibility", $data);
    }
}
