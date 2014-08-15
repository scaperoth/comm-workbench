<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class AddgadgetimageForm extends CFormModel {

    public $image_name;
    public $campus;
    public $building;
    public $room;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('image_name, campus, building, room', 'required'),
        );
    }

    /**
     * adds an image to the file system
     * @return boolean whether login is successful
     */
    public function addimage() {
        $building = $this->building ? $this->building . DIRECTORY_SEPARATOR : '';
        $room = $this->room ? $this->room . DIRECTORY_SEPARATOR : '';
        $image = urlencode($this->image_name);
        $location = $this->campus . DIRECTORY_SEPARATOR . $building . $room . $image;
        
        $url = Yii::app()->createAbsoluteUrl("api/addimage/gadgets/$location");
        $curl_response = Yii::app()->curl->get($url);
        
        if (!$curl_response) {
            return false;
        }
        
        
        $url = Yii::app()->createAbsoluteUrl("api/update/gadgets/save");
        $curl_response = Yii::app()->curl->get($url);
       
        if (!$curl_response) {
            return false;
        }

        return true;
    }
    
     /**
     * removes an image from the file system
     * @return boolean whether login is successful
     */
    public function removeimage() {
        $image = $this->image_name;
            
        $url = Yii::app()->createAbsoluteUrl("api/removeimage/gadgets/$image");
        
        $curl_response = Yii::app()->curl->get($url);
        
        
        $url = Yii::app()->createAbsoluteUrl("api/update/gadgets/save");
        $curl_response = Yii::app()->curl->get($url);
        
        if (!$curl_response) {
            return false;
        }

        return true;
    }

}
