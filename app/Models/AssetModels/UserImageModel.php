<?php

namespace App\Models\AssetModels;

use CodeIgniter\Model;

class UserImageModel extends Model
{
    protected $table = 'user_image';

    protected $allowedFields = [
        'id',
        'user_id',
        'image_name'
    ];

    protected $primaryKey = 'id';
}
