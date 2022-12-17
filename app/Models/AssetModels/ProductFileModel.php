<?php

namespace App\Models\AssetModels;

use CodeIgniter\Model;
use Exception;

class ProductFileModel extends Model
{
    protected $table = 'product_file';

    protected $allowedFields = [
        'id',
        'product_id',
        'file_name',
        'file_type'
    ];

    protected $primaryKey = 'id';

    
    /**
     * @param $file
     * @param $productId
     */
    public function saveSystemAndDB($file, $productId){
        try{

            if ($file->isValid() && !$file->hasMoved()) {
                $nameFile = $file->getRandomName();
                
                $mimeType=$file->getMimeType();
                $fileType = explode('/', $mimeType)[0];
                
                $fileFolder = ROOTPATH . 'public/UploadedFiles/products/';
                $file->move($fileFolder, $nameFile);
                $this->save([
                    "file_name" => $nameFile,
                    "product_id" => $productId,
                    "file_type" => $fileType
                ]);
            }
        }
        catch (Exception $e){
            throw $e;
        }
    }

    /**
     * @param $file a database file entry
     * @return true when success, false if unsuccessful
     */
    public function removeSystemAndDB($file){
        try{
            $file_location = ROOTPATH . 'public/UploadedFiles/products/' . $file["file_name"];
            // unlink returns TRUE if removing file succeeded (source: https://www.php.net/unlink)
                if (unlink($file_location)) {
                    $this->delete($file['id']); //delete from database as well
                    return true;
                }
                else{
                    return false;
                }
            }
        catch(Exception $e){
            throw $e;
        }
    }

    public function getFilesByUser($id){
        $files = $this->where("product_id", $id)->get()->getResultArray();
        return array_values($files);
    }
}
