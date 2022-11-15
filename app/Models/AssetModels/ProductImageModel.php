<?php

namespace App\Models\AssetModels;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table = 'product_image';

    protected $allowedFields = [
        'id',
        'product_id',
        'image_name'
    ];

    protected $primaryKey = 'id';

    
    /**
     * @param $imageFile
     * @param $productId
     */
    public function saveSystemAndDB($imageFile, $productId){
        if ($imageFile->isValid() && !$imageFile->hasMoved()) {
            $nameImg = $imageFile->getRandomName();
            $imgFolder = ROOTPATH . 'public/UploadedFiles/productImages/';
            $imageFile->move($imgFolder, $nameImg);
            $this->save([
                "image_name" => $nameImg,
                "product_id" => $productId
            ]);
        }
    }

    /**
     * @param $image a database image entry
     * @return true when success, false if unsuccessful
     */
    public function removeSystemAndDB($image){
        $image_location = ROOTPATH . 'public/UploadedFiles/productImages/' . $image["image_name"];
        // unlink returns TRUE if removing file succeeded (source: https://www.php.net/unlink)
        if (unlink($image_location)) {
            $this->delete($image['id']); //delete from database as well
            return true;
        }
        else{
            return false;
        }
    }
}
