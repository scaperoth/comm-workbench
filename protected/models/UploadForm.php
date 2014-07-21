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
        $url = Yii::app()->createAbsoluteUrl('api/bucketdir/' . $this->service.'/full');
        $uploaddir = Yii::app()->curl->get($url);
        $uploaddir = str_replace("\"", "", $uploaddir);
        $uploaddir = trim($uploaddir) . "/";
        
        //file:///C:/xampp/htdocs/comm-workbench/themes/bootstrap/assets/images/gadget_images/
        //C:\xampp\htdocs\comm-workbench\themes\bootstrap\assets\images\gadget_images/
        //C:/xampp/htdocs/comm-workbench/themes/bootstrap/assets/images/gadget_images/
         $extension = strtolower($this->getExtension( $this->name));
        
        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            echo ' Unknown Image extension ';
            return false;
        }
        if (move_uploaded_file($this->tmp_name, $uploaddir . $this->name)) {

            echo "File is valid, and was successfully uploaded.\n";
        } else {

            echo "Possible file upload attack!\n";
        }
        
        ApiHelper::_create_thumbnail($this->name, $uploaddir, $extension);

        return true;
    }

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

