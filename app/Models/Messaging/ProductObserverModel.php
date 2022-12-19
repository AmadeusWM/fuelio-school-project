<?php

namespace App\Models\Messaging;

use CodeIgniter\Model;

class ProductObserverModel extends Model
{
    protected $table = 'product_observer';

    protected $allowedFields = [
        "id",
        "user_id",
        "product_id",
    ];

    protected $primaryKey = 'id';

    public function observe($productId)
    {
        $data = [
            "user_id" => session("id"),
            "product_id" => $productId,
        ];

        $this->save($data);
    }
}
