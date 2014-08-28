<?php

/**
 * UploadForm class.
 * UploadForm is the data structure for keeping
 * user upload form data. It is used by the 'login' action of 'GadgetsController'.
 */
class UploadForm extends CFormModel {

    public $name;
    public $type;
    public $tmp_name;
    public $error;
    public $size;
    public $service;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('tmp_name', 'required'),
            array('type', 'required'),
            array('size', 'required'),
            array('error', 'required'),
            array('name', 'required'),
        );
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function upload() {
        $uploaddir = ApiHelper::_get_bucket_url($this->service);

        $uploaddir = str_replace("\"", "", $uploaddir);
        $uploaddir = trim($uploaddir);
        $uploaddir = dirname(Yii::getPathOfAlias('webroot')) . $uploaddir;

        $uploaddir = urldecode(str_replace("\\", "", $uploaddir));
        //file:///C:/xampp/htdocs/comm-workbench/themes/bootstrap/assets/images/gadget_images/
        //C:\xampp\htdocs\comm-workbench\themes\bootstrap\assets\images\gadget_images/
        //C:/xampp/htdocs/comm-workbench/themes/bootstrap/assets/images/gadget_images/
        $extension = strtolower($this->getExtension($this->name));

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            echo ' Unknown Image extension ';
            return false;
        }
        if (move_uploaded_file($this->tmp_name, $uploaddir . $this->name)) {

            echo "File is valid, and was successfully uploaded.\n";
        } else {

            echo "Possible file upload attack!\n";
            return false;
        }

        self::_create_thumbnail($this->name, $uploaddir, $extension);

        return true;
    }

    /**
     * generates thumbnail from given image
     * @param type $image_name name of image to generate
     * @param type $uploaddir location of original image
     * @param type $extension image type extension
     * @return string success or failure
     */
    public static function _create_thumbnail($image_name, $uploaddir, $extension) {
        $uploadedfile = $uploaddir . "/" . $image_name;

        //create thumbnail
        if ($extension == "jpg" || $extension == "jpeg") {
            $src = imagecreatefromjpeg($uploadedfile);
        } else if ($extension == "png") {
            $src = imagecreatefrompng($uploadedfile);
        } else {
            $src = imagecreatefromgif($uploadedfile);
        }

        list($width, $height) = getimagesize($uploadedfile);

        $newwidth = 120;
        $newheight = ($height / $width) * $newwidth;
        $tmp = imagecreatetruecolor($newwidth, $newheight);

        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        $filename = $uploaddir . "thumb/thumb_" . $image_name;

        imagegif($tmp, $filename, 100);

        imagedestroy($src);
        imagedestroy($tmp);

        return 'success';
    }

    /**
     * returns file extension
     * @param type $str name of image to strip file extension
     * @return string extension
     */
    function getExtension($str) {

        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

}

