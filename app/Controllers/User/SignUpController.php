<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;

class SignUpController extends BaseController
{

    public function index()
    {
        $data = [
            'title' => ucfirst("Register")
        ];

        return $this->page("user/register", $data);
    }

    /**
     * Source: https://www.positronx.io/codeigniter-authentication-login-and-registration-tutorial/
     */
    public function register()
    {
        helper(['form']);

        $data['title'] = 'Register';
        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();

        $data['fulfilled'] = false;

        $rules = [
            'username'          => 'required|min_length[2]|max_length[64]',
            'email'             => 'required|min_length[4]|max_length[128]|valid_email|is_unique[user.email]',
            'password'          => 'required|min_length[4]|max_length[256]',
            'confirmpassword'   => 'matches[password]'
        ];
        
        if($this->validate($rules)){
            $userModel = new UserModel();
            $data = [
                'username'     => $this->request->getVar('username'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $userModel->save($data);
            $data['fulfilled'] = true;
        }else{
            $data['validation_errors'] = $this->validator->getErrors();
        }
        return $this->response->setJSON($data);
    }
}
