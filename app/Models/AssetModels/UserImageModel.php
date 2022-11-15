<?php

namespace App\Models\AssetModels;

use CodeIgniter\Model;

class UserImageModel extends Model
{
    protected $table = 'user_image';

    protected $allowedFields = [
        'id',
        'user_id',
        'image_name'
    ];

    protected $primaryKey = 'id';

    /**
     * @param $imageFile uploaded image from user
     * @param $userId
     */
    public function saveSystemAndDB($imageFile, $userId)
    {
        if ($imageFile->isValid() && !$imageFile->hasMoved()) {
            $nameImg = $imageFile->getRandomName();
            $imgFolder = ROOTPATH . 'public/UploadedFiles/userImages/';
            $imageFile->move($imgFolder, $nameImg);
            $this->save([
                "image_name" => $nameImg,
                "user_id" => $userId
            ]);
        }
    }

    /**
     * @param $image a database image entry
     * @return true when success, false if unsuccessful
     */
    public function removeSystemAndDB($image)
    {
        $image_location = ROOTPATH . 'public/UploadedFiles/userImages/' . $image["image_name"];
        // unlink returns TRUE if removing file succeeded (source: https://www.php.net/unlink)
        if (unlink($image_location)) {
            $this->delete($image['id']); //delete from database as well
            return true;
        } else {
            return false;
        }
    }
}
