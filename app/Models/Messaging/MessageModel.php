<?php

namespace App\Models\Messaging;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'message';

    protected $allowedFields = [
        "id",
        "sender_id",
        "receiver_id",
        "title",
        "content",
        "type",
        "pointer_id"
    ];

    protected $primaryKey = 'id';

    /**
     * @param $idSender
     * @param $idReceiver
     * @param $title
     * @param $content
     * @param $type
     * @param $pointerId
     * @pre $type is one of the following "message","stock", "review"
     * @pre if $type is either "stock" or "review", then the $pointerId must reference a product
     */
    public function sendMessage($idSender, $idReceiver, $title, $content, $type = "message", $pointerId = null)
    {
        $data = [
            "sender_id" => $idSender,
            "receiver_id" => $idReceiver,
            "title" => $title,
            "content" => $content,
            "type" => $type,
            "pointer_id" => $pointerId
        ];

        $this->save($data);
    }

    public function getMessagesByUser($id)
    {
        return $this->where("receiver_id", $id)
        ->limit(100)
        ->select("message.*, user.username AS sender_username, user.webshop_name AS sender_webshop")
        ->join("user", "message.sender_id = user.id", "left")
        ->orderBy("message.id", "desc")
        ->get()->getResultArray();
    }
}
