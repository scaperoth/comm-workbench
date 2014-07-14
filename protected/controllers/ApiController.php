<?php

/**
 * @author: Matt Scaperoth
 * @date: 7-7-14
 * 
 * API for communications dashboard application.
 * This api uses the ApiHelper class to call various functions
 * This ApiHelper can be found in protected/components/. It extends CHTML.
 */
class ApiController extends Controller {

    //source and destination locations. A prefix of the application's base path
    //will be added before the action is exectured in beforeAction(). This base
    //path here is expected to be ../protected/components/
    private $gadgets_shared = '\\data\\backup\\';
    private $gadgets_local = '\\data\\filesystem\\';
    private $gadgets_bucket = "\\assets\\images\\gadget_images";

    public function beforeAction($action) {
        $this->gadgets_shared = Yii::app()->basePath . $this->gadgets_shared;
        $this->gadgets_local = Yii::app()->basePath . $this->gadgets_local;
        $this->gadgets_bucket = dirname(Yii::getPathOfAlias('webroot')) . Yii::app()->theme->baseUrl . $this->gadgets_bucket;
        return parent::beforeAction($action);
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
     * Syncs /protected/data/filesystem contents with current filesystem in remote server
     * or vice versa
     */
    public function actionSync() {

        if ($push_or_pull = CHttpRequest::getParam('push_or_pull')) {
            $JSON_array = ApiHelper::_ProcessSync($push_or_pull, $this->gadgets_shared, $this->gadgets_local);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    public function actionUpdate() {
        if ($load_or_save = CHttpRequest::getParam('load_or_save')) {
            switch ($load_or_save) {
                case 'load':
                    $JSON_array = ApiHelper::_load_from_db_save_to_local($this->gadgets_local);
                    break;
                case 'save':
                    $JSON_array = ApiHelper::_save_to_db_load_from_local($this->gadgets_local);
                    break;
                default:
                    $JSON_array = ApiHelper::_save_to_db_load_from_local($this->gadgets_local);
                    break;
            }
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    public function actionImagedir() {
        if ($image_name = CHttpRequest::getParam('image_name')) {
            switch ($image_name) {
                case 'all':
                    $JSON_array = ApiHelper::_find_all_image_parent($this->gadgets_local, $this->gadgets_bucket);
                    break;
                default:
                    $JSON_array = ApiHelper::_find_image_parent($image_name, $this->gadgets_local);
                    break;
            }
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

}