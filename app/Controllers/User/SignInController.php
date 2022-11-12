<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;

class SignInController extends BaseController
{
    public function index()
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
    public function login()
    {
        $session = session(); // access and initialize session (https://www.codeigniter.com/user_guide/libraries/sessions.html#initializing-a-session)
        $userModel = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $user = $userModel->where('email', $email)->first();
        
        if($user){
            $pass = $user['password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
                $ses_data = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
            }else{
                $session->set('isLoggedIn', false);
                $session->setFlashdata('msg', 'Password is incorrect.');
            }
        }else{
            $session->set('isLoggedIn', false);
            $session->setFlashdata('msg', 'Email does not exist.');
        }
        // csrf token for ajax
        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();

        // return response for ajax
        $data["session"] = $session->get();
        $data["session-flash-data"] = $session->getFlashdata();
        return $this->response->setJSON($data);
    }

    public function logout()
    {
        $session = session();
        
        $session->set('isLoggeIn', false);
        session_destroy();

        return redirect()->to(base_url("/"));
    }
}
