<?php

namespace App\Controllers\User\Account;

use App\Controllers\BaseController;
use App\Controllers\User\Account\OverviewController;
use App\Models\AssetModels\ProductFileModel;
use App\Models\Messaging\MessageModel;
use App\Models\Messaging\ProductObserverModel;
use App\Models\UserModel;
use App\Models\Products\ProductCategoryModel;
use App\Models\Products\ProductModel;

class ProductsController extends BaseController
{
    private function productInputRules()
    {
        return [
            'name'                  => 'required|min_length[1]|max_length[256]',
            'price'                 => 'required|numeric|min_length[1]|max_length[12]',
            'description'           => 'required|min_length[1]|max_length[2048]',
            'origin'                => 'required|min_length[1]|max_length[128]',
            'quantity'              => 'required|numeric|min_length[1]|max_length[12]',
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


        return $this->page("product/productAdd", $data);
    }

    public function editProductPage($productId)
    {
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();
        helper(['form']);

        $data = $productModel->getProductDataById($productId);
        $data['title'] = ucfirst("Edit Product");

        $product_categories = $productCategoryModel->findAll();
        $data['product_categories'] = $product_categories;

        if (session("id") != $data["user_id"]){
            session()->setFlashdata("errors", "<ul><li>You don't own this product</li></ul>");
            return redirect()->to(base_url("/failure"));
        }

        return $this->page("product/productEdit", $data);
    }


    public function addProduct()
    {
        $data = [];

        $userModel = new UserModel();
        $productModel = new ProductModel();
        $productCategoryModel = new ProductCategoryModel();
        $productFileModel = new ProductFileModel();

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
            $quantity = $this->request->getVar('quantity');
            $productCategory = $productCategoryModel->where("name", $categoryName)->first();
            // return error when product category doesn't exist
            if (!isset($productCategory)) {
                $data['validation'] = $this->validator;
                return $this->addProductPage($data);
            }
            $data = [
                'user_id' => $user['id'],
                'name'      => $this->request->getVar('name'),
                'price'       => $this->request->getVar('price'),
                'description'    => $this->request->getVar('description'),
                'origin'         => $this->request->getVar('origin'),
                'quantity'            => $quantity,
                'product_category_id' => $productCategory['id'],
            ];

            $productId = 0;

            // edit product
            if ($this->request->getPost('productId')) {
                $productId = $this->request->getPost('productId');
                $product = $productModel->find($productId);
                if (session("id") != $product["user_id"]){
                    session()->setFlashdata("errors", "<ul><li>You don't own this product</li></ul>");
                    return redirect()->to(base_url("/failure"));
                }
                $data['id'] = $productId;
                $productModel->save($data);
                $productId = $data['id'];
                if ($quantity > 0)
                    $this->notifyObservers($productId);
            } else { // add product
                $productId = $productModel->insert($data, true); // true => returns insert id
            }


            // add files to database
            if ($files) {
                foreach ($files as $file) {
                    $productFileModel->saveSystemAndDB($file, $productId);
                }
            }

            return redirect()->to(base_url('/account/overview/products'));
        } else { // something went wrong, send back validation errors
            return $this->addProductPage($data);
        }
    }

    /**
     * Notifies all observers, observing the product with
     * @param $productid
     */
    private function notifyObservers($productId)
    {
        $productObserverModel = new ProductObserverModel();
        $productModel = new ProductModel();
        $messageModel = new MessageModel();

        $product = $productModel->find($productId);
        $productName = ucfirst($product["name"]);

        $senderId = session("id");
        $observers = $productObserverModel->where("product_id", $productId)
            ->get()->getResultArray();

        foreach ($observers as $observer) {
            $messageModel->sendMessage(
                $senderId,
                $observer["user_id"],
                "Product Reminder",
                "$productName is back in stock!",
                "stock",
                $productId
            );
            $productObserverModel->delete($observer["id"]);
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
