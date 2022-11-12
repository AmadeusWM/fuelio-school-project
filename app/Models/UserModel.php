<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';

    protected $allowedFields = [
        'username',
        'email',
        'password',
        'webshop_name',
        'description',
        'business_email',
        'telephone',
        'mobile',
        'website',
        'other',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'id';
}
