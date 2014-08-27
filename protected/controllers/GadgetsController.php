<?php

class GadgetsController extends Controller {

    public function filters() {
        return array(
            'https',
            array(
                'application.filters.AuthFilter',
            ),
        );
    }

    public function actionIndex() {

        $bucket_dir = ApiHelper::_get_bucket_url('gadgets');
        $dbstructure = ApiHelper::_get_db_structure('gadgets');
        $bucket_files = $dbstructure['bucket'];
        $root = ApiHelper::_get_local_path('gadgets');

        foreach ($bucket_files as $image) {
            if (!is_array($image)) {
                $image_parent = ApiHelper::_find_image_parent($image, $root);
                $image_locations[] = $image_parent;
            }
        }
        $this->render('index', array(
            'image_locations' => $image_locations,
            'dbstructure' => $dbstructure,
            'bucket_dir' => $bucket_dir,
            'bucket_files' => $bucket_files
        ));
    }

    public function action_image_section() {
        $this->render('_image_section');
    }

    public function action_location_section() {
        $this->render('_location_section');
    }

    /* ############################################
     * GADGET actions
     * ############################################/

      /**
     * 
     * @throws CHttpException
     */

    public function actionAddlocation() {
        if (!YII_DEBUG && !Yii::app()->request->isPostRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }


        $model = new AddimageForm;

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