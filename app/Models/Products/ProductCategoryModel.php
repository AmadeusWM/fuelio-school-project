<?php

namespace App\Models\Products;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table = 'product_category';

    protected $allowedFields = [
        'id',
        'name',
        'description'
    ];

    protected $primaryKey = 'id';
}
