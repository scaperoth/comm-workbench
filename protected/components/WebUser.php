<?php
// this file must be stored in:
// protected/components/WebUser.php
 
class WebUser extends CWebUser {
 
  // Store model to not repeat query.
  private $_model;
 
  // This is a function that checks the field 'role'
  // in the User model to be equal to 1, that means it's admin
  // access it by Yii::app()->user->isAdmin()
  function isManager(){
    $user = $this->loadUser(Yii::app()->user->id);
    if($user){
       
        $return =  strcmp($user->permission_type, "manager") == 0;
    } else $return=0;
    
    return $return;;
  }
  
  function isEmployee(){
    $user = $this->loadUser(Yii::app()->user->id);
    if($user){
       
        $return =  strcmp($user->permission_type, "employee")==0 || strcmp($user->permission_type, "manager") == 0;
    } else $return=0;
    
    return $return;;
  }
  
  function isUser(){
    $user = $this->loadUser(Yii::app()->user->id);
    if($user){
        $return =  strcmp($user->permission_type, "manager")==0 || strcmp($user->permission_type, "employee")==0 || strcmp($user->permission_type, "user") == 0;
    } else $return=0;
    
    return $return;;
  }
 
  // Load user model.
  protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null){
                $user_permission_id = HasPermissions::model()->findByAttributes(array('usr_id' => $id));
                $this->_model = Permissions::model()->findByPk($user_permission_id['permission_id']);
            }
        }
        return $this->_model;
    }
}
?>