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
    private $gadgets_shared = '\\data\\gadgets\\backup\\';
    private $gadgets_local = '\\data\\gadgets\\filesystem\\';
    private $gadgets_bucket = "\\assets\\images\\gadget_images";
    private $wepa_shared = '\\data\\wepa\\backup\\';
    private $wepa_local = '\\data\\wepa\\filesystem\\';
    private $wepa_bucket = "\\assets\\images\\wepa_images\\production_images";
    private $wepa_outage_bucket = "\\assets\\images\\wepa_images\\outage_images";
    
    private $gadgets_shared_full;
    private $gadgets_local_full;
    private $gadgets_bucket_full;
    private $wepa_shared_full;
    private $wepa_local_full;
    private $wepa_bucket_full;
    private $wepa_outage_bucket_full;
    
    public function beforeAction($action) {
//gadgets
        $this->gadgets_shared_full = Yii::app()->basePath . $this->gadgets_shared;
        $this->gadgets_local_full = Yii::app()->basePath . $this->gadgets_local;
        $this->gadgets_bucket_full = dirname(Yii::getPathOfAlias('webroot')) . Yii::app()->theme->baseUrl . $this->gadgets_bucket;

//wepa
        $this->wepa_shared_full = Yii::app()->basePath . $this->wepa_shared;
        $this->wepa_local_full = Yii::app()->basePath . $this->wepa_local;
        $this->wepa_bucket_full = dirname(Yii::getPathOfAlias('webroot')) . Yii::app()->theme->baseUrl . $this->wepa_bucket;

        return parent::beforeAction($action);
    }

    /**
     * this may be used to display how to use api
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
     * @throws CHttpException
     */
    public function actionSync() {
//first find out which service the API will be accessing
        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = self::_get_service(CHttpRequest::getParam('which_service'));

            if ($push_or_pull = CHttpRequest::getParam('push_or_pull')) {
                switch ($push_or_pull) {
                    case 'push':
                        $JSON_array = self::_sync_from_source($service_details['local_file'], $service_details['shared_file']);
                        break;
                    case 'pull':
                        $JSON_array = self::_sync_from_source($service_details['shared_file'], $service_details['local_file']);
                        break;

                    default:
                        throw new CHttpException(404, "The API for 'api/sync/$service/$push_or_pull' cannot be found.");
                }
                $JSON_array = ApiHelper::_ProcessSync($push_or_pull, $service_details['shared_file'], $service_details['local_file']);
            }
            else
                throw new CHttpException(404, "The sub-page you are looking for does not exist.");
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * synchronizes databse with local filesystem
     * @throws CHttpException
     */
    public function actionUpdate() {

        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = self::_get_service(CHttpRequest::getParam('which_service'));

//now which action will the service be completeing
            if ($load_or_save = CHttpRequest::getParam('load_or_save')) {
                switch ($load_or_save) {
                    case 'load':
                        $JSON_array = ApiHelper::_load_from_db_save_to_local($service_details['local_file'], $service_details['database']);
                        break;
                    case 'save':
                        $JSON_array = ApiHelper::_save_to_db_load_from_local($service_details['local_file'], $service_details['database']);
                        break;
                    default:
                        throw new CHttpException(404, "The API for 'api/update/$service/$load_or_save' cannot be found.");
                        break;
                }
            }
            else
                throw new CHttpException(404, "The sub-page you are looking for does not exist.");
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * returns image directory for given image
     * or all image directories if "all" passed
     * @throws CHttpException
     */
    public function actionGetdir() {
//first find out which service the API will be accessing and get the details of that service
        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = self::_get_service($service);

//if an image name is given, get the directory of that image, else get them all
            if ($image_name = CHttpRequest::getParam('image_name')) {
                $JSON_array = ApiHelper::_find_image_parent($image_name, $service_details['local_file']);
            }
            else
                $JSON_array = ApiHelper::_find_all_image_parent($service_details['local_file'], $service_details['bucket']);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * copies all images from local file system to bucket of images 
     * to synchronize assets
     * if an image is specified it only adds that single image
     * @throws CHttpException
     */
    public function actionPutimage() {
//first find out which service the API will be accessing and get the details of that service

        if (CHttpRequest::getParam('which_service')) {
            $service = CHttpRequest::getParam('which_service');
            $service_details = self::_get_service($service);

//if an image name is given, insert that image, else sync them all

            if (isset($_FILES)) {

//$image_name = CHttpRequest::getParam('image_name');
                $JSON_array = ApiHelper::_add_image_to_bucket($service_details['bucket'], $_FILES['new_image']);
            }
            else
                $JSON_array = ApiHelper::_fill_bucket($service_details['local_file'], $service_details['bucket']);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * removes all images from bucket if no image is specified
     * if an image is specified it deletes that single image
     * @throws CHttpException
     */
    public function actionDeleteimage() {
//first find out which service the API will be accessing and get the details of that service
        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = self::_get_service(CHttpRequest::getParam('which_service'));
//if an image name is given, insert that image, else sync them all
            if ($image_name = CHttpRequest::getParam('image_name')) {
                $JSON_array = ApiHelper::_remove_image_from_bucket($service_details['bucket'], $image_name);
            }
            else
//removes all images from bucket
                $JSON_array = ApiHelper::_remove_image_from_bucket($service_details['bucket']);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * 
     */
    public function actionBucketdir() {
        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = self::_get_service($service);
//if an image name is given, insert that image, else sync them all
            
            
            $JSON_array = $service_details['bucket'];
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }
    
    /**
     * 
     */
    public function actionFilestructure() {
        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = self::_get_service($service);
//if an image name is given, insert that image, else sync them all

            $JSON_array = ApiHelper::_load_db_structure($service_details['database']);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }
    
    /**
     * 
     */
    public function actionBucketfiles() {
        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = self::_get_service($service);
//if an image name is given, insert that image, else sync them all

            $JSON_array = ApiHelper::_ReadFolderDirectory($service_details['bucket']);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /* ####################################
     * Other helpful functions...
     * #################################### */

    /**
     * returns which service is being selected
     * is located here instead of the helper in order to use the
     * local variables for file names and databases
     * @param type $param
     * @return type
     */
    private function _get_service($param) {
        $service_details = array();
        switch ($param) {
            case 'gadgets':
                $service_details['local_file'] = $this->gadgets_local_full;
                $service_details['shared_file'] = $this->gadgets_shared_full;
                $service_details['bucket'] = $this->gadgets_bucket_full;
                $service_details['database'] = Yii::app()->mongodb->gadgets;
                break;
            case 'wepa':
                $service_details['local_file'] = $this->wepa_local_full;
                $service_details['shared_file'] = $this->wepa_shared_full;
                $service_details['bucket'] = $this->wepa_bucket_full;
                $service_details['outage_bucket'] = $this->wepa_outage_bucket_full;
                $service_details['database'] = Yii::app()->mongodb->wepa;

                break;
        }


        return $service_details;
    }

}