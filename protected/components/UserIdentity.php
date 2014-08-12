<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $data = array(
            'netid' => $this->username,
            'password' => $this->password,
            'group'=>'staff',
        );
        $ch = curl_init('http://auth.acadtech.gwu.edu/authenticate');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Length: ' . strlen($data["netid"]) + strlen($data["password"])+strlen($data["group"]))
        );

        $result = curl_exec($ch);
        $json_obj = json_decode($result);
        
        if (!empty($json_obj->success)) {
            $this->errorCode = self::ERROR_NONE;
        } else if (!empty($json_obj)) {
            Yii::app()->user->setFlash('danger', $json_obj->error);
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            Yii::app()->user->setFlash('warning', 'Request Failed: '.print_r($json_obj));
        }
        
        
        return !$this->errorCode;
    }

}