<?php

namespace App\Controllers\Messaging;

use App\Controllers\BaseController;
use App\Models\Messaging\MessageModel;
use App\Models\Messaging\ProductObserverModel;
use App\Models\UserModel;
use Exception;

class MessagingController extends BaseController
{
    public function messageSenderPage($id)
    {
        $userModel = new UserModel();
        $receiver = $userModel->getPublicUserData($id);
        if (isset($receiver)) {
            $data["title"] = $receiver["username"];
            $data["receiver"] = $receiver;
            return $this->page("messaging/messageSender", $data);
        } else {
            session()->setFlashdata("errors", "<ul><li>Invalid Receiver</li></ul>");
            return redirect()->to(base_url("/failure"));
        }
    }

    /**
     * Send a message to a user
     * @param $id: id of message receiver
     */
    public function messageUser($id)
    {
        $userModel = new UserModel();
        $messageModel = new MessageModel();
        $session = session();

        $sender = $userModel->find($session->get("id"));
        $receiver = $userModel->find($id);
        if (isset($sender) && isset($receiver)) {
            $rules = [
                "title" => "required|min_length[1]|max_length[128]",
                "content" => "required|min_length[1]|max_length[1024]",
            ];
            if (!$this->validate($rules)) {
                $session->setFlashdata("errors", $this->validator->listErrors());
                return redirect()->to(base_url("/failure"));
            }

            $title = $this->request->getVar("title");
            $content = $this->request->getVar("content");

            $messageModel->sendMessage($sender["id"], $receiver["id"], $title, $content);

            $session->setFlashdata("messsage", "Message sent successfully.");
            return redirect()->to(base_url("/success"));
        } else {
            $session->setFlashdata("errors", "<ul><li>Invalid Receiver Or Sender</li></ul>");
            return redirect()->to(base_url("/failure"));
        }
    }

    public function notifyLater($id){
        $productObserver = new ProductObserverModel();
        $productObserver->observe($id);
        session()->setFlashdata("message", "You will be notified when this product is back in stock.");
        return redirect()->to(base_url("/success"));
    }
}
