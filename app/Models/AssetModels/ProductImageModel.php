<?php

namespace App\Models\AssetModels;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table = 'product_image';

    protected $allowedFields = [
        'id',
        'product_id',
        'image_name'
    ];

    protected $primaryKey = 'id';
}
