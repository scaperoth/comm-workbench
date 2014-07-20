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
    
    public function action_image_section() {
        $this->render('_image_section');
    }
    
    public function action_location_section() {
        $this->render('_location_section');
    }
    

    /**
     * 
     * @throws CHttpException
     */
    public function actionLocationajax() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        if (empty($_POST['args'])) {
            throw new CHttpException('404', 'Missing "group" POST parameter.');
        }
        $location = '';
        foreach ($_POST['args'] as $arg) {
            $location.= $arg . "/";
        }
        $url = Yii::app()->createAbsoluteUrl("api/filestructure/gadgets/$location");
        $curl_response = Yii::app()->curl->get($url);
        header('Content-Type: application/json; charset="UTF-8"');

        echo $curl_response;
        Yii::app()->end();
    }

    public function actionAddlocation() {
        if (!YII_DEBUG && !Yii::app()->request->isPostRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $model = new AddgadgetimageForm;

        // collect user input data
        if (isset($_POST['AddimageForm'])) {
            $model->attributes = $_POST['AddimageForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->addimage()) {
                Yii::app()->user->setFlash('success', 'location added');
            } else {
                Yii::app()->user->setFlash('warning', 'Request Failed');
            }
        }
        // display the original form form
        $this->redirect(array('gadgets/'));
    }

    public function actionSave() {
        $url = Yii::app()->createAbsoluteUrl("api/update/gadgets/save");
        $curl_response = Yii::app()->curl->get($url);
        
        Yii::app()->user->setFlash('success', 'Refreshed');
        
        $this->redirect(array('gadgets/'));
    }

    public function actionLoad() {
        $url = Yii::app()->createAbsoluteUrl("api/sync/gadgets/pull"); 
        $curl_response = Yii::app()->curl->get($url);
        
        $url = Yii::app()->createAbsoluteUrl("api/update/gadgets/save");
        $curl_response = Yii::app()->curl->get($url);
        
        Yii::app()->user->setFlash('success', 'Loaded');
        $this->redirect(array('gadgets/'));
    }

    public function actionPublish() {
        $url = Yii::app()->createAbsoluteUrl("api/update/gadgets/save");
        $curl_response = Yii::app()->curl->get($url);
        
        $url = Yii::app()->createAbsoluteUrl("api/sync/gadgets/push");
        $curl_response = Yii::app()->curl->get($url);
        
        Yii::app()->user->setFlash('success', 'Published');
        
        $this->redirect(array('gadgets/'));
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