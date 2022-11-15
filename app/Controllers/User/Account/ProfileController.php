<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Controllers\User\Account\OverviewController;
use App\Models\UserModel;
use App\Models\AssetModels\UserImageModel;

class ProfileController extends BaseController
{
    public function index($data = [])
    {
        $overviewController = new OverviewController();

        $data['title'] = ucfirst("profile");
        $data['page'] = $this->view($data);

        return $overviewController->index($data);
    }

    private function view($data = [])
    {
        helper(['form']);
        $session = session(); // access and initialize session (https://www.codeigniter.com/user_guide/libraries/sessions.html#initializing-a-session)
        $userModel = new UserModel();
        $userImageModel = new UserImageModel();

        $user = $userModel->find($session->get('id'));

        $images = $userImageModel->where('user_id', $user['id'])->get()->getResultArray();

        $data = $data + [
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
        $files = $this->request->getFiles();
        $images = $files["img_files"];
        // use isValid() to check if a file has been uploaded at all.
        if ($images && array_values($images)[0]->isValid()) {
            $rules['img_files'] = [
                'label' => 'Image File',
                'rules' => 'uploaded[img_files]'
                    . '|is_image[img_files]'
                    . '|mime_in[img_files,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                    . '|max_size[img_files,10000]' // 10mb files 
            ];
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
            if ($images) {
                foreach ($images as $image) {
                    $userImageModel->saveSystemAndDB($image, $user['id']);
                }
            }

            return redirect()->to(base_url('/account/overview/profile'));
        } else { // something went wrong, send back validation errors
            $data['validation'] = $this->validator;
            return $this->index($data);
        }
    }

    public function removeImage()
    {
        helper(['filesystem']);
        $userImageModel = new UserImageModel();

        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();
        $data['success'] = false;

        $imageId = $this->request->getVar('imageId');
        if (!$imageId) {
            return $this->response->setJSON($data);
        }

        $data['imageId'] = $imageId;

        $image = $userImageModel->find($imageId);

        if($image && $userImageModel->removeSystemAndDB($image)){
                $data['success'] = true;
                return $this->response->setJSON($data);
        }
        return $this->response->setJSON($data);
    }
}
