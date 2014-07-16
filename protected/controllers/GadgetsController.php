<?php

class GadgetsController extends Controller {

    public function filters() {
        return array(
            array(
                'application.filters.AuthFilter',
            ),
        );
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionUpload() {
        $model = new UploadForm();
// if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

// collect user input data
        if (isset($_FILES['file'])) {
            $model->attributes = $_FILES['file'];
// validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->upload()) {
                Yii::app()->user->setFlash('success','image uploaded!');
                $this->redirect(array('gadgets/upload'));
            }
        }
// display the login form
        $this->render('upload', array('model' => $model));
    }

// Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}