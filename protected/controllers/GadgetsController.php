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
        $bucket_files = ApiHelper::_get_bucket_files('gadgets');
        $bucket_dir = ApiHelper::_get_bucket_url('gadgets');
        $dbstructure = ApiHelper::_get_db_structure('gadgets');
        
        foreach ($bucket_files as $image) {
            if (!is_array($image)) {
                $image = urlencode($image);
                $url = Yii::app()->createAbsoluteUrl("api/getdir/gadgets/$image");
                $curl_response = Yii::app()->curl->get($url);

                $image_locations[] = (json_decode($curl_response, true));
            }
        }
        $this->render('index', array('image_locations' => $image_locations, 'dbstructure' => $dbstructure, 'bucket_dir' => $bucket_dir, 'bucket_files' => $bucket_files));
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

    /* ############################################
     * AJAX actions
     * ############################################ */

    public function actionDrawlocationsajax() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $dbstructure = ApiHelper::_get_db_structure('gadgets');

        $bucket_dir = $_POST['args']['bucketdir'];
        $root = $_POST['args']['root'];
        $campus = $_POST['args']['campus'];
        $building = $_POST['args']['building'];

        switch ($_POST['args']['hierarchy']) {
            case 'files':
                $dbstructure = $dbstructure['files'];
                break;
            case 'root':
                $dbstructure = $dbstructure['files'][$campus];

                break;
            case 'subfolder':
                $dbstructure = $dbstructure['files'][$campus][$building];
                break;
            default:
                break;
        }

        ApiHelper_Gadgets::draw_gadget_location_one_directory($dbstructure, $root, $bucket_dir, $campus, $building);
    }

    /**
     * 
     * @throws CHttpException
     */
    public function actionGetlocationdataajax() {
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

    /**
     * 
     * @throws CHttpException
     */
    public function actionAddlocationajax() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        if (empty($_POST['args'])) {
            throw new CHttpException('404', 'Missing "group" POST parameter.');
        }

        $model = new AddgadgetimageForm;

// collect user input data
        if (isset($_POST['args'])) {

            $model->attributes = $_POST['args'];
            $image = urldecode($_POST['args']['image_name']);
            $model->image_name = $image;
// validate user input and redirect to the previous page if valid
            if ($model->addimage()) {
                
            } else {
                Yii::app()->user->setFlash('warning', 'Request Failed');
            }
        }
    }

    /**
     * 
     * @throws CHttpException
     */
    public function actionRemovelocationfromimageajax() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        if (empty($_POST['image_name'])) {
            throw new CHttpException('404', 'Missing "image_name" POST parameter.');
        }

        $model = new AddgadgetimageForm;

// collect user input data
        if (isset($_POST['image_name'])) {
            $model->image_name = $_POST['image_name'];

// validate user input and redirect to the previous page if valid
            if ($model->removeimage()) {
                
            } else {
                Yii::app()->user->setFlash('warning', 'Request Failed');
            }
        }
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