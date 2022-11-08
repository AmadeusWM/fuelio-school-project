<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class UserModel extends Model{
    protected $table = 'user';
    
    protected $allowedFields = [
        'username',
        'email',
        'password',
        'created_at'
    ];

    protected $primaryKey = 'id';
}