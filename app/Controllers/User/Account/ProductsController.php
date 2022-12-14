<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Controllers\User\Account\OverviewController;
use App\Models\AssetModels\ProductFileModel;
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
            'description'           => 'required|min_length[1]|max_length[500]',
            'origin'                => 'required|min_length[1]|max_length[50]',
            'quantity'              => 'required|numeric|min_length[1]|max_length[50]',
            'product_category'      => 'required|min_length[1]|max_length[50]',
        ];
    }

    private function fileInputRules()
    {
        return [
            'label' => 'File',
            'rules' => 'uploaded[files]'
                . '|mime_in[files,image/jpg,image/jpeg,image/gif,image/png,image/webp,video/mp4]'
                . '|max_size[files,10000]' // 10mb files 
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
        $productCategoryModel = new ProductCategoryModel();
        helper(['form']);

        $product_categories = $productCategoryModel->findAll();

        $data['title'] = ucfirst("Add Product");
        $data['product_categories'] = $product_categories;


        return view('templates/header', $data) .
            view('product/productAdd') .
            view('templates/footer');
    }

    public function editProductPage($productId)
    {
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();
        helper(['form']);

        $data = $productModel->getProductDataById($productId);
        
        $product_categories = $productCategoryModel->findAll();
        $data['product_categories'] = $product_categories;

        // TODO check if userid of product is id of session

        return view('templates/header', ['title' => ucfirst("Edit Product")]) .
            view('product/productEdit', $data) .
            view('templates/footer');
    }


    public function addProduct()
    {
        // TODO: if productId parameter set: check if 
        // the session user has the same id as the product
        $data = [];

        $userModel = new UserModel();
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();
        $ProductFileModel = new ProductFileModel();

        // find current logged in user
        $session = session();
        $user = $userModel->find($session->get('id'));

        // set rules for input
        $rules = $this->productInputRules();
        // rules for all the files
        $files = $this->request->getFiles();
        $files = $files["files"];
        // use isValid() to check if a file has been uploaded at all.
        if ($files && array_values($files)[0]->isValid()) {
            $rules['files'] = $this->fileInputRules();
        }


        if ($this->validate($rules) && $user) {
            // find category id
            $categoryName = $this->request->getVar('product_category');
            $productCategory = $productCategoryModel->where("name", $categoryName)->first();
            // return error when product category doesn't exist
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


            // add files to database
            if ($files) {
                foreach ($files as $file) {
                    $ProductFileModel->saveSystemAndDB($file, $productId);
                }
            }

            return redirect()->to(base_url('/account/overview/products'));
        } else { // something went wrong, send back validation errors
            return $this->addProductPage($data);
        }
    }

    public function removeProduct()
    {
        $userModel = new UserModel();
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();
        $productFileModel = new ProductFileModel();

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

        $files = $productFileModel->where('product_id', $product['id'])->get()->getResultArray();

        foreach ($files as $file) {
            if (!$productFileModel->removeSystemAndDB($file)) {
                return $this->response->setJSON($data);
            }
        }

        // product can be removed from model if all the previous tasks were executed successfully
        if ($productModel->delete($product['id'])) {
            $data['success'] = true;
        }
        return $this->response->setJSON($data);
    }

    public function removeFile()
    {
        helper(['filesystem']);
        $productFileModel = new ProductFileModel();

        $data['csrf_value'] = csrf_hash();
        $data['csrf_token'] = csrf_token();
        $data['success'] = false;

        $fileId = $this->request->getVar('fileId');
        if (!$fileId) {
            return $this->response->setJSON($data);
        }

        $data['fileId'] = $fileId;

        $file = $productFileModel->find($fileId);

        if ($file && $productFileModel->removeSystemAndDB($file)) {
            $data['success'] = true;
            return $this->response->setJSON($data);
        }
        return $this->response->setJSON($data);
    }
}
