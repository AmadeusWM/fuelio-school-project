<?php

namespace App\Models;

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
    ];

    protected $primaryKey = 'id';

    public function sendMessage($idSender, $idReceiver, $title, $content)
    {
        $data = [
            "sender_id" => $idSender,
            "receiver_id" => $idReceiver,
            "title" => $title,
            "content" => $content
        ];
        
        $this->save($data);
    }
}
