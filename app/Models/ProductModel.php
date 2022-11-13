<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'product';

    protected $allowedFields = [
        'id',
        'user_id',
        'name',
        'price',
        'description',
        'origin',
        'quantity',
        'product_category_id',
    ];

    protected $primaryKey = 'id';
}
