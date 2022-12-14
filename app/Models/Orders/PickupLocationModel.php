<?php

namespace App\Models\Orders;

use CodeIgniter\Model;

class PickupLocationModel extends Model
{
    protected $table = 'pickup_location';

    protected $allowedFields = [
        "id",
        "name",
        "street",
        "house_number",
        "city",
        "postal_code",
        "country",
    ];

    protected $primaryKey = 'id';
}
