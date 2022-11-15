<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Controllers\User\Account\OverviewController;
use App\Models\AssetModels\ProductImageModel;
use App\Models\UserModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;

class ProductsController extends BaseController
{
    private function productInputRules()
    {
        return [
            'name'                  => 'required|min_length[1]|max_length[50]',
            'price'                 => 'required|numeric|min_length[1]|max_length[50]',
            'description'           => 'required|min_length[1]|max_length[50]',
            'origin'                => 'required|min_length[1]|max_length[50]',
            'quantity'              => 'required|numeric|min_length[1]|max_length[50]',
            'product_category'      => 'required|min_length[1]|max_length[50]',
        ];
    }

    private function imageInputRules()
    {
        return [
            'label' => 'Image File',
            'rules' => 'uploaded[img_files]'
                . '|is_image[img_files]'
                . '|mime_in[img_files,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                . '|max_size[img_files,10000]' // 10mb files 
        ];
    }

    public function index($data = [])
    {
        $overviewController = new OverviewController();

        $data['title'] = ucfirst("products");
        $data['page'] = $this->view($data);

        return $overviewController->index($data);
    }

    private function view($data = [])
    {
        $session = session(); // access and initialize session (https://www.codeigniter.com/user_guide/libraries/sessions.html#initializing-a-session)
        $userModel = new UserModel();
        $productModel = new ProductModel();

        $user = $userModel->find($session->get('id'));


        $data["products"] = $productModel->getProductsFromUser($user['id']);


        return view("user/account/products", $data);
    }

    public function addProductPage()
    {
        helper(['form']);
        $data['title'] = ucfirst("Add Product");

        return view('templates/header', $data) .
            view('product/productAdd') .
            view('templates/footer');
    }

    public function editProductPage($productId)
    {
        $productModel = new ProductModel();
        helper(['form']);

        $data = $productModel->getProductDataById($productId);

        // TODO check if userid of product is id of session

        return view('templates/header', ['title' => ucfirst("Edit Product")]) .
            view('product/productEdit', $data) .
            view('templates/footer');
    }


    public function addProduct()
    {
        $userModel = new UserModel();
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();
        $productImageModel = new ProductImageModel();

        // find current logged in user
        $session = session();
        $user = $userModel->find($session->get('id'));

        // set rules for input
        $rules = $this->productInputRules();
        // rules for all the images
        $files = $this->request->getFiles();
        $images = $files["img_files"];
        // use isValid() to check if a file has been uploaded at all.
        if ($images && array_values($images)[0]->isValid()) {
            $rules['img_files'] = $this->imageInputRules();
        }


        if ($this->validate($rules) && $user) {
            // find category id
            $categoryName = $this->request->getVar('product_category');
            $productCategory = $productCategoryModel->where("name", $categoryName)->first();
            // return error when 
            if (!$productCategory) { // TODO show proper error
                $data['validation'] = $this->validator;
                return $this->addProductPage($data);
            }
            $data = [
                'user_id' => $user['id'],
                'name'      => $this->request->getVar('name'),
                'price'       => $this->request->getVar('price'),
                'description'    => $this->request->getVar('description'),
                'origin'         => $this->request->getVar('origin'),
                'quantity'            => $this->request->getVar('quantity'),
                'product_category_id' => $productCategory['id'],
            ];

            $productId = 0;

            if ($this->request->getPost('productId')) {
                $data['id'] = $this->request->getPost('productId');
                $productModel->save($data);
                $productId = $data['id'];
            } else {
                $productId = $productModel->insert($data, true); // true => returns insert id
            }


            // add images
            if ($images) {
                foreach ($images as $image) {
                    $productImageModel->saveSystemAndDB($image, $productId);
                }
            }

            return redirect()->to(base_url('/account/overview/products'));
        } else { // something went wrong, send back validation errors
            $data['validation'] = $this->validator;
            return $this->addProductPage($data);
        }
    }

    public function removeProduct()
    {
        $userModel = new UserModel();
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();
        $productImageModel = new ProductImageModel();

        // to update csrf data
        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();

        // find current logged in user
        $session = session();
        $user = $userModel->find($session->get('id'));

        $productId = $this->request->getVar('productId');
        $data['productId'] = $productId; // return id of removed product in response

        $product = $productModel->find($productId);

        $data['success'] = false;

        // check if product of logged in user
        if ($product['user_id'] != $user['id']) {
            return $this->response->setJSON($data);
        }

        $images = $productImageModel->where('product_id', $product['id'])->get()->getResultArray();

        foreach ($images as $image) {
            if (!$productImageModel->removeSystemAndDB($image)) {
                return $this->response->setJSON($data);
            }
        }

        // product can be removed from model if all the previous tasks were executed successfully
        if ($productModel->delete($product['id'])) {
            $data['success'] = true;
        }
        return $this->response->setJSON($data);
    }

    public function removeImage()
    {
        helper(['filesystem']);
        $productImageModel = new ProductImageModel();

        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();
        $data['success'] = false;

        $imageId = $this->request->getVar('imageId');
        if (!$imageId) {
            return $this->response->setJSON($data);
        }

        $data['imageId'] = $imageId;

        $image = $productImageModel->find($imageId);

        if ($image && $productImageModel->removeSystemAndDB($image)) {
            $data['success'] = true;
            return $this->response->setJSON($data);
        }
        return $this->response->setJSON($data);
    }
}
