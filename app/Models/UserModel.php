<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\AssetModels\UserImageModel;
use Exception;

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

    public function getPublicUserData($id)
    {
        $userImageModel = new UserImageModel();
        $user = $this->select("id, username, webshop_name, description, business_email, telephone, mobile, website, other")
            ->find($id);
        if (isset($user)) {
            $user["images"] = $userImageModel->where("user_id", $id)->get()->getResultArray();
            return $user;
        } else {
            throw new Exception("Invalid User");
        }
    }
}
