<?php

namespace App\Models;

use CodeIgniter\Model;

class UserImageModel extends Model
{
    protected $table = 'user_image';

    protected $allowedFields = [
        'id',
        'user_id',
        'image_location'
    ];

    protected $primaryKey = 'id';
}
