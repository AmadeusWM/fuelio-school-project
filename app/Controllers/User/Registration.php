<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Registration extends BaseController
{

    public function registerView()
    {
        $data = [
            'title' => ucfirst("Register")
        ];

        return view('templates/header', $data) .
            view('user/register') .
            view('templates/footer');
    }

    public function loginView()
    {
        $data = [
            'title' => ucfirst("Log in")
        ];

        return view('templates/header', $data) .
            view('user/login') .
            view('templates/footer');
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
            'username'          => 'required|min_length[2]|max_length[50]',
            'email'             => 'required|min_length[4]|max_length[100]|valid_email|is_unique[user.email]',
            'password'          => 'required|min_length[4]|max_length[50]',
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

    public function login()
    {
        $session = session();
        $userModel = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $data = $userModel->where('email', $email)->first();
        
        if($data){
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
                $ses_data = [
                    'id' => $data['id'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to('/profile');
            }else{
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to('/signin');
            }
        }else{
            $session->setFlashdata('msg', 'Email does not exist.');
            return redirect()->to('/signin');
        }
    }
}
