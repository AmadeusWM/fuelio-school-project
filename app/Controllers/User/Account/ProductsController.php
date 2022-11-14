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
        $productCategoryModel = new ProductCategoryModel();
        $productImageModel = new ProductImageModel();

        $user = $userModel->find($session->get('id'));

        $products = $productModel->where("user_id", $user['id'])->get()->getResultArray();;


        $productsData = array();

        foreach ($products as $product) {
            $images = $productImageModel->where('product_id', $product['id'])->get()->getResultArray();
            $productCategory = $productCategoryModel->find($product['product_category_id']);
            
            $toImageName = function($n) {
                return $n['image_name'];
            };
            
            $imageNames = array_map($toImageName, $images);
            
            array_push($productsData, [
                'id'                  => $product['id'],
                'name'                  => $product['name'],
                'price'                 => $product['price'],
                'description'           => $product['description'],
                'origin'                => $product['origin'],
                'quantity'              => $product['quantity'],
                'product_category'      => $productCategory['name'],
                'images'                => array_values($imageNames)
            ]);
        }

        $data["products"] = $productsData;


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
        $rules = [
            'name'                  => 'min_length[0]|max_length[50]',
            'price'                 => 'numeric|min_length[0]|max_length[50]',
            'description'           => 'min_length[0]|max_length[50]',
            'origin'                => 'min_length[0]|max_length[50]',
            'quantity'              => 'numeric|min_length[0]|max_length[50]',
            'product_category'      => 'min_length[0]|max_length[50]',
        ];

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

        // find category id
        $categoryName = $this->request->getVar('product_category');
        $productCategoryId = $productCategoryModel->where("name", $categoryName)->first()['id'];

        if ($this->validate($rules) && $user && $productCategoryId) {
            $data = [
                'user_id' => $user['id'],
                'name'      => $this->request->getVar('name'),
                'price'       => $this->request->getVar('price'),
                'description'    => $this->request->getVar('description'),
                'origin'         => $this->request->getVar('origin'),
                'quantity'            => $this->request->getVar('quantity'),
                'product_category_id' => $productCategoryId,
            ];

            $productId = $productModel->insert($data, true); // true => returns insert id

            // add images
            if ($images) {
                foreach ($images as $image) {
                    if ($image->isValid() && !$image->hasMoved()) {
                        $nameImg = $image->getRandomName();
                        $imgFolder = ROOTPATH . 'public/UploadedFiles/productImage/';
                        $image->move($imgFolder, $nameImg);
                        $productImageModel->save([
                            "image_name" => $nameImg,
                            "product_id" => $productId
                        ]);
                    }
                }
            }

            return redirect()->to(base_url('/account/overview/products'));
        } else { // something went wrong, send back validation errors
            $data['validation'] = $this->validator;
            return $this->index($data);
        }
    }
}
