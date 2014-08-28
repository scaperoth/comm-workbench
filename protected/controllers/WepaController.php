<?php

class WepaController extends Controller {

    public function filters() {
        return array(
            'https',
            array(
                'application.filters.AuthFilter',
            ),
        );
    }

    public function actionIndex() {
        $page = 'image';
        if (isset($_GET['page_id'])) {
            $page = $_GET['page_id'];
        }

        $bucket_dir = ($page === 'outage') ? ApiHelper::_get_outage_bucket_url() : ApiHelper::_get_bucket_url('wepa');
        $dbstructure = ApiHelper::_get_db_structure('wepa');
        $bucket_files = $dbstructure['bucket'];
        $outage_bucket_files = $dbstructure['outage_bucket'];
        $root = ApiHelper::_get_local_path('wepa');
        $isoutage = ApiHelper::_is_outage();

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
            'bucket_files' => $bucket_files,
            'outage_bucket_files' => $outage_bucket_files,
            'isoutage' => $isoutage,
        ));
    }

    public function action_image_section() {
        $this->render('_image_section');
    }

    public function action_location_section() {
        $this->render('_location_section');
    }

    public function action_outage_section() {
        $this->render('_outage_section');
    }

    public function actiontoggleoutage() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $outage = array(
            'isoutage' => !ApiHelper::_is_outage(),
        );
        Yii::app()->mongodb->admin->save($outage);
        
        echo $outage['isoutage'];
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