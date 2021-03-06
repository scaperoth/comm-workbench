<?php

class SiteController extends Controller {

    public function filters() {
        return array(
            'https',
            array(
                'application.filters.AuthFilter  - login, logout',
            ),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            $this->render('index');
        } else {
            $model = new LoginForm;

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if ($model->validate() && $model->login())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
            // display the login form
            $this->render('login', array('model' => $model));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * upload document to the proper bucket directory
     */
    public function actionUpload() {

        $errors = 0;
        $model = new UploadForm();
// if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
// collect user input data
        if (isset($_FILES['file'])) {

            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {


                $model->name = $_FILES['file']['name'][$i];
                $model->type = $_FILES['file']['type'][$i];
                $model->tmp_name = $_FILES['file']['tmp_name'][$i];
                $model->error = $_FILES['file']['error'][$i];
                $model->size = $_FILES['file']['size'][$i];
                $model->service = $_POST['service'];

// validate user input and redirect to the previous page if valid
                if (!$model->validate() || !$model->upload()) {

                    $errors = 1;
                }
            }
            if (!$errors) {
                if ($_POST['service'] === 'wepa-outage')
                    $url = Yii::app()->createAbsoluteUrl("api/update/wepa/save");
                else
                    $url = Yii::app()->createAbsoluteUrl("api/update/{$_POST['service']}/save");
                $curl_response = Yii::app()->curl->get($url);
                Yii::app()->user->setFlash('success', 'image(s) uploaded!');
            }
            else
                Yii::app()->user->setFlash('warning', 'unable to upload file!');
            $this->redirect(array('site/upload'));
        }
// display the upload form
        $this->render('upload', array('model' => $model));
    }

    /**
     * 
     */
    public function actionSave() {
        if (!isset($_GET['service'])) {
            throw new CHttpException('403', 'Invalid access.');
        }
        $service = $_GET['service'];
        $url = Yii::app()->createAbsoluteUrl("api/update/$service/save");
        $curl_response = Yii::app()->curl->get($url);

        Yii::app()->user->setFlash('success', 'Refreshed');

        $this->redirect("../" . $service);
    }

    /**
     * 
     */
    public function actionLoad() {
        if (!isset($_GET['service'])) {
            throw new CHttpException('403', 'Invalid access.');
        }
        $service = $_GET['service'];
        $url = Yii::app()->createAbsoluteUrl("api/sync/$service/pull");
        $curl_response = Yii::app()->curl->get($url);

        $url = Yii::app()->createAbsoluteUrl("api/update/$service/save");
        $curl_response = Yii::app()->curl->get($url);

        Yii::app()->user->setFlash('success', 'Loaded');
        $this->redirect("../" . $service);
    }

    /**
     * 
     */
    public function actionPublish() {
        if (!isset($_GET['service'])) {
            throw new CHttpException('403', 'Invalid access.');
        }

        $service = $_GET['service'];
        $url = Yii::app()->createAbsoluteUrl("api/update/$service/save");
        $curl_response = Yii::app()->curl->get($url);

        $url = Yii::app()->createAbsoluteUrl("api/sync/$service/push");
        $curl_response = Yii::app()->curl->get($url);

        Yii::app()->user->setFlash('success', 'Published');

        $this->redirect("../" . $service);
    }

    /* ############################################
     * AJAX actions
     * ############################################ */

    public function actionDrawlocationsajax() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $service = $_POST['service'];

        $dbstructure = ApiHelper::_get_db_structure($service);
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

        ApiHTML::draw_location_one_directory($dbstructure, $root, $bucket_dir, $campus, $building);
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
        $service = $_POST['service'];
        foreach ($_POST['args'] as $arg) {
            $location.= $arg . "/";
        }

        $url = Yii::app()->createAbsoluteUrl("api/filestructure/$service/$location");
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

        $model = new ChangeImageForm;

// collect user input data
        if (isset($_POST['args'])) {

            $model->attributes = $_POST['args'];
            $image = urldecode($_POST['args']['image_name']);
            $model->image_name = $image;
            $model->service = $_POST['service'];
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
        if (empty($_POST['args'])) {
            throw new CHttpException('404', 'Missing "image_name" POST parameter.');
        }

        $model = new ChangeImageForm;

// collect user input data
        if (isset($_POST['args'])) {
            $model->image_name = $_POST['args']['image_name'];
            $model->service = $_POST['service'];


// validate user input and redirect to the previous page if valid
            if ($model->removeimage()) {
                
            } else {
                Yii::app()->user->setFlash('warning', 'Request Failed');
            }
        }
    }

}