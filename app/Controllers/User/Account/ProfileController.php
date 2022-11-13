<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserImageModel;

class ProfileController extends BaseController
{
    public function index($data = [])
    {

        $data['title'] = ucfirst("Your Account");
        $data['page'] = $this->view();

        return view('templates/header', $data) .
            view('user/account/overview', $data) .
            view('templates/footer');
    }

    public function viewResponse()
    {
        return $this->response->setJSON(["view" => $this->view()]);
    }

    private function view()
    {
        helper(['form']);
        $session = session(); // access and initialize session (https://www.codeigniter.com/user_guide/libraries/sessions.html#initializing-a-session)
        $userModel = new UserModel();
        $userImageModel = new UserImageModel();

        $user = $userModel->find($session->get('id'));

        $images = $userImageModel->where('user_id', $user['id'])->get()->getResultArray();

        $data = [
            'webshop_name'     => $user['webshop_name'],
            'description'      => $user['description'],
            'business_email'   => $user['business_email'],
            'telephone'        => $user['telephone'],
            'mobile'           => $user['mobile'],
            'website'          => $user['website'],
            'other'            => $user['other'],
            'images'           => $images
        ];

        return view("user/account/profile", $data);
    }

    public function updateProfile()
    {
        helper(['form']);

        $userModel = new UserModel();
        $userImageModel = new UserImageModel();
        $session = session();
        $user = $userModel->find($session->get('id'));

        if (!$user)
            return redirect()->to(base_url('/'));

        $rules = [
            'webshop_name'      => 'min_length[0]|max_length[50]',
            'description'       => 'min_length[0]|max_length[500]',
            'website'           => 'min_length[0]|max_length[50]',
            'other'             => 'min_length[0]|max_length[500]',
        ];

        if ($this->request->getPost('business_email')) {
            $rules['business_email'] = 'min_length[0]|max_length[50]|valid_email';
        }
        if ($this->request->getPost('telephone')) {
            $rules['telephone'] = 'numeric|min_length[0]|max_length[15]';
        }
        if ($this->request->getPost('mobile')) {
            $rules['mobile'] = 'numeric|min_length[0]|max_length[15]';
        }

        // rules for all the images
        // source: https://codeigniter4.github.io/CodeIgniter4/libraries/uploaded_files.html
        if ($images = $this->request->getFiles()) {
            $i = 0;
            foreach ($images['img_files'] as $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    // add a rule for every uploaded image... ($i because: img_files.0, img_files.1, img_files.2,... is how the individual files are selected)
                    $rules['img_files.' . $i] = [
                        'label' => 'Image File ' . $i,
                        'rules' => 'uploaded[img_files.' . $i . ']'
                            . '|is_image[img_files.' . $i . ']'
                            . '|mime_in[img_files.' . $i . ',image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                            . '|max_size[img_files.' . $i . ',10000]' // 10mb files 
                    ];
                    $i++;
                }
            }
        }

        if ($this->validate($rules)) {
            // update user
            $data = [
                'id' => $user['id'],
                'webshop_name'      => $this->request->getVar('webshop_name'),
                'description'       => $this->request->getVar('description'),
                'business_email'    => $this->request->getVar('business_email'),
                'telephone'         => $this->request->getVar('telephone'),
                'mobile'            => $this->request->getVar('mobile'),
                'website'           => $this->request->getVar('website'),
                'other'             => $this->request->getVar('other'),
            ];
            $userModel->save($data);

            // add images
            if ($images = $this->request->getFiles()) {
                foreach ($images['img_files'] as $image) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $nameImg = $image->getRandomName();
                        $imgFolder = ROOTPATH . 'public/userImages/';
                        $image->move($imgFolder, $nameImg);
                        $userImageModel->save([
                            "image_location" => '/userImages/' . $nameImg,
                            "user_id" => $user["id"]
                        ]);
                    }
                }
            }

            return redirect()->to(base_url('/account/overview/profile'));
        } else { // something went wrong, send back validation errors
            $data['validation'] = $this->validator;
            echo $this->index($data);
        }
    }

    public function removeImage()
    {
        helper(['filesystem']);
        $userImageModel = new UserImageModel();
        
        $imageId = $this->request->getVar('imageId');
        $image = $userImageModel->where('id', $imageId)->first();

        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();
        $data['success'] = false;
        $data['imageId'] = $imageId;
        
        if ($image){
            $image_location = ROOTPATH . 'public' . $image["image_location"];
            // unlink returns TRUE if removing file succeeded
            if (unlink($image_location)){
                $userImageModel->delete($imageId); //delete from database as well
                $data['success'] = true;
                return $this->response->setJSON($data);
            }
        }
        return $this->response->setJSON($data);
    }
}
