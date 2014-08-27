<?php

/**
 * @author: Matt Scaperoth
 * @date: 7-7-14
 * 
 * API for communications dashboard application.
 * This api uses the ApiHelper class to call various functions
 * This ApiHelper can be found in protected/components/. It extends CHTML.
 */
class ApiController extends CController {

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
            $service_details = ApiHelper::_get_service(CHttpRequest::getParam('which_service'));

            if ($push_or_pull = CHttpRequest::getParam('push_or_pull')) {
                switch ($push_or_pull) {
                    case 'push':
                        $JSON_array = self::_sync_from_source($service_details['local_file'], $service_details['shared_file'], $service_details['database']);
                        break;
                    case 'pull':
                        $JSON_array = self::_sync_from_source($service_details['shared_file'], $service_details['local_file'], $service_details['database']);
                        break;

                    default:
                        throw new CHttpException(404, "The API for 'api/sync/$service/$push_or_pull' cannot be found.");
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
     * synchronizes databse with local filesystem
     * @throws CHttpException
     */
    public function actionUpdate() {

        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = ApiHelper::_get_service(CHttpRequest::getParam('which_service'));

//now which action will the service be completeing
            if ($load_or_save = CHttpRequest::getParam('load_or_save')) {
                switch ($load_or_save) {
                    case 'load':
                        $JSON_array = self::_load_from_db_save_to_local($service_details['local_file'], $service_details['database']);
                        break;
                    case 'save':
                        $JSON_array = self::_save_to_db_load_from_local($service_details['local_file'], $service_details['database'], $service_details['bucket']);
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
            $service_details = ApiHelper::_get_service($service);

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
     * Adds an image to a specified location in local file system
     * @throws CHttpException
     */
    public function actionAddimage() {
//first find out which service the API will be accessing and get the details of that service
        $which_path;
        if (CHttpRequest::getParam('which_service')) {
            $service = CHttpRequest::getParam('which_service');
            $service_details = ApiHelper::_get_service($service);

            if ($image_name = CHttpRequest::getParam('image_name')) {
                $JSON_array = self::_add_image_to_files($image_name, $service_details['local_file'], $service_details['bucket']);
            } else {
                throw new CHttpException(404, "The API for 'api/addimage/$service/$image_name' cannot be found.");
            }
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * Removes image from specific location in local file system
     * @throws CHttpException
     */
    public function actionRemoveimage() {
//first find out which service the API will be accessing and get the details of that service
        if (CHttpRequest::getParam('which_service')) {
            $service = CHttpRequest::getParam('which_service');
            $service_details = ApiHelper::_get_service($service);


            if ($image_name = CHttpRequest::getParam('image_name')) {
                $JSON_array = self::_remove_image_from_files($service_details['local_file'], $image_name);
                $JSON_array = CHttpRequest::getParam('image_name');
            } else {
                throw new CHttpException(404, "The API for 'api/removeimage/$service/$image_name' cannot be found.");
            }
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
    public function actionPutimageinbucket() {
//first find out which service the API will be accessing and get the details of that service

        if (CHttpRequest::getParam('which_service')) {
            $service = CHttpRequest::getParam('which_service');
            $service_details = ApiHelper::_get_service($service);

//if an image name is given, insert that image, else sync them all

            if (Yii::app()->request->isPostRequest) {
//$image_name = CHttpRequest::getParam('image_name');
                $JSON_array = self::_add_image_to_bucket($service_details['bucket'], $_POST, $service_details['database']);
            } else {
                $JSON_array = self::_fill_bucket($service_details['local_file'], $service_details['bucket']);
            }
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
    public function actionDeleteimageinbucket() {
//first find out which service the API will be accessing and get the details of that service
        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = ApiHelper::_get_service(CHttpRequest::getParam('which_service'));
//if an image name is given, insert that image, else sync them all
            if ($image_name = CHttpRequest::getParam('image_name')) {
                $JSON_array = self::_remove_image_from_bucket($service_details['bucket'], $image_name);
            }
            else
//removes all images from bucket
                $JSON_array = self::_remove_image_from_bucket($service_details['bucket']);
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
            $service_details = ApiHelper::_get_service($service);
//if an image name is given, insert that image, else sync them all


            if ($which_type = CHttpRequest::getParam('which_type')) {
                if ($which_type == 'full')
                    $JSON_array = $service_details['bucket'];
            }
            else
//removes all images from bucket
                $JSON_array = Yii::app()->theme->baseUrl . ApiHelper::GADGETS_BUCKET;
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
            $service_details = ApiHelper::_get_service($service);
//if an image name is given, insert that image, else sync them all

            $JSON_array = ApiHelper::_ReadFolderDirectory_from_local($service_details['bucket']);
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
            $service_details = ApiHelper::_get_service($service);
//if an image name is given, insert that image, else sync them all
            if ($subdirectory = CHttpRequest::getParam('subdirectory')) {
                if ($bottomdirectory = CHttpRequest::getParam('bottomdirectory')) {
                    $JSON_array = ApiHelper::_ReadFolder_subdirectory($service, $subdirectory, $bottomdirectory);
                }
                else
                    $JSON_array = ApiHelper::_ReadFolder_subdirectory($service, $subdirectory);
            }
            else
                $JSON_array = ApiHelper::_ReadFolder_subdirectory($service);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /**
     * 
     */
    public function actionDbstructure() {
        if ($service = CHttpRequest::getParam('which_service')) {
            $service_details = ApiHelper::_get_service($service);
//if an image name is given, insert that image, else sync them all

            $JSON_array = ApiHelper::_ReadFolderDirectory_from_db($service_details['database']);
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        ApiHelper::_sendResponse(200, CJSON::encode($JSON_array));
    }

    /* ####################################
     * Other helpful functions...
     * #################################### */

    /**
     * 
     * @param type $str
     * @return string
     */
    private static function getExtension($str) {

        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    /* #############################################
     * File manipulation
     * COULD BE OPTIMIZED
     * ############################################# */

    /**
     * abstracts the delete and copy functions
     * returns array of the files in the destination directory
     * @param type $source
     * @param type $dest
     * @return type
     */
    public static function _sync_from_source($source, $dest, $which_db) {
        $destination_array = array();
        $counter = 0;

//first check to see if there's anything in the local fs that doesn't belong
        self::_delete_from_dest($source, $dest);

//now copy over all systems that do belong from source fs
        self::_copy_from_source($source, $dest);
        $destination_array['destination folder'] = $dest;
        $destination_array['contents'] = ApiHelper::_ReadFolderDirectory_from_db($which_db);
        /*
          echo '<pre>';
          print_r($destination_array);
          echo '</pre>';
         * 
         */
        return $destination_array;
    }

    /**
     * deletes all files from the destination directory that don't exist in the
     * source directory to prepare it to copy over new files
     * code pulled from http://stackoverflow.com/questions/5707806/recursive-copy-of-directory
     * @param type $source
     * @param type $dest
     */
    public static function _delete_from_dest($source, $dest) {
//first check to see if there's anything in the destination fs that doesn't belong
        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dest, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if (!file_exists($source . DIRECTORY_SEPARATOR . $iterator->getSubPathName())) {
                self::_delete_files($item);
            }
        }
    }

    /**
     * copies all files (that don't already exist) from the source directory, and subdirectories, into the
     * code pulled from http://stackoverflow.com/questions/5707806/recursive-copy-of-directory
     * destination directory
     * @param type $source
     * @param type $dest
     */
    public static function _copy_from_source($source, $dest) {
//now copy over all systems that do belong from source fs
        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {

//make sure file/folder doesn't already exist first
            if (!file_exists($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName())) {
                if ($item->isDir()) {
                    mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                } else {
                    copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                }
            }
        }
    }

    /**
     * php delete function that deals with directories recursively
     * @param type $target
     */
    public static function _delete_files($target) {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                self::_delete_files($file);
            }
            if (file_exists($target))
                rmdir($target);
        }
        elseif (!is_object($target) && !strpos($target, 'Thumbs.db')) {
            unlink($target);
        } elseif (is_file($target) && $target->getFilename() != 'Thumbs.db') {
            unlink($target);
        }
    }

    /**
     * removes image from source
     * @param type $source directory to remove image
     * @param type $image_name name of image to be removed
     * @return string path and name of image that was removed
     */
    public static function _remove_image_from_files($source, $image_name) {
        self::_delete_files($source . $image_name);
        return $source . $image_name;
    }

    /* #############################################
     * Image manipulation
     * ############################################# */

    /**
     * inserts an image into given destination in given source
     * @param type $image_name
     * @param type $dest
     * @param type $source
     * @return string|boolean
     */
    public static function _add_image_to_files($image_name, $dest, $source) {
        $newpath = $image_name;
        $image_name = basename($image_name);

        $source = dirname(Yii::getPathOfAlias('webroot')) . $source;

        if ($newpath == 'GWU' . DIRECTORY_SEPARATOR)
            $newpath = '';
        $return = "error with: $source/$image_name, $dest/$newpath";
        if (copy($source . DIRECTORY_SEPARATOR . $image_name, $dest . $newpath)) {

            return "File is valid, and was successfully uploaded.\n";
        } else {
            return false;
        }
        return $return;
    }

    /* #############################################
     * Bucket manipulation
     * ############################################# */

    /**
     * fills image bucket from source. 
     * bucket found in themes/bootstrap/images/gadget_images/
     * @param type $source
     * @param type $bucket
     */
    public static function _fill_bucket($source, $bucket) {
        $model = new UploadForm();
        $supported_images = array(
            'gif',
            'jpg',
            'jpeg',
            'png'
        );

        $bucket_files = array();
        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if (!$item->isDir()) {
                $file = $iterator->current();
                $file = pathinfo($file->getPath() . "/" . $file->getFilename());
                $ext = $file['extension'];

                if (in_array($ext, $supported_images) && !in_array($file['basename'], $bucket_files)) {
//echo 'Image found!</br>';
                    $bucket_files[] = $file['basename'];
                    copy($item, $bucket . DIRECTORY_SEPARATOR . $file['basename']);
                    $model->_create_thumbnail($file['basename'], $bucket, $ext);
                } else {
//echo 'not an image</br>';
                }
            }
        }
        return $bucket_files;
    }

    /**
     * adds a specific image to the bucket
     * @param type $source
     * @param type $bucket
     */
    public static function _add_image_to_bucket($uploaddir, $file, $which_db) {
        $bucket_files = array(
        );
        $uploaddir = dirname(Yii::getPathOfAlias('webroot')) . $uploaddir;
        $filename = $file['new_image']['name'];
        $tmpname = $file['new_image']['tmp_name'];
        //file:///C:/xampp/htdocs/comm-workbench/themes/bootstrap/assets/images/gadget_images/
        //C:\xampp\htdocs\comm-workbench\themes\bootstrap\assets\images\gadget_images/
        //C:/xampp/htdocs/comm-workbench/themes/bootstrap/assets/images/gadget_images/
        $extension = strtolower(self::getExtension($filename));


        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            $bucket_files = ' Unknown Image extension ';
        }
        if (move_uploaded_file($tmpname, "$uploaddir/$filename")) {

            ApiHelper::_create_thumbnail($filename, $uploaddir, $extension);
            $bucket_files = ApiHelper::_ReadFolderDirectory_from_db($which_db);
            $bucket_files = $bucket_files['bucket'];
        } else {

            $bucket_files = "Possible file upload attack!\n";
        }

        return $bucket_files;
    }

    /**
     * removes a specific image from the bucket
     * @param type $bucket path to image top directory to iterate through
     * @param type $image_name name of image to be removed
     * @return string names of images that were deleted
     */
    public static function _remove_image_from_bucket($bucket, $image_name = '') {
        $message = "Success";
        $delete_images = array();
        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($bucket, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if (!$item->isDir()) {
                if ($image_name == '' || $image_name == $item->getFilename()) {
                    self::_delete_files($item);
                    $deleted_images['images'] = $item->getFilename();
                }
            }
        }

        if (empty($deleted_images))
            $message = 'File not found.';

        $delete_images['message'] = $message;
        return $delete_images;
    }

    /* #############################################
     * Dataase manipulation
     * ############################################# */

    /**
     * translates the file system into a mongo db
     * @param type $local
     */
    public static function _save_to_db_load_from_local($local, $which_db, $bucket) {
        $which_db->remove();
        $bucket = dirname(Yii::getPathOfAlias('webroot')) . $bucket;

        $r = array(
            "bucket" => ApiHelper::_ReadFolderDirectory_from_local($bucket),
            "timestamp" => date('m-d-y h:i:s'),
            "files" => array(),
        );

        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($local, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->getFilename() != 'Thumbs.db' && $item->getFilename() != 'Block.txt') {
                if ($item->isDir()) {
                    switch ($iterator->getDepth()) {
                        case 0:
                            $path = array("root" => array($item->getFilename() => array()));
                            break;
                        case 1:
                            $path = array("subfolder" => array($item->getFilename() => array()));
                            break;
                        case 2:
                            $path = array("bottomfolder" => array($item->getFilename() => array()));
                            $path[$item->getFilename()] = array();
                            break;
                        default:
                            $path = array("bottomfolder" => array($item->getFilename() => array()));
                            $path[$item->getFilename()] = array();
                            break;
                    }
                } else {
                    $path = array("images" => array($item->getFilename()));
                }

                for ($depth = $iterator->getDepth() - 1; $depth >= 0; $depth--) {
                    $path = array($iterator->getSubIterator($depth)->current()->getFilename() => $path);
                }
                $r["files"] = array_merge_recursive($r["files"], $path);
            }
        }

        //array_multisort(array_keys($r), SORT_STRING, $r);

        print_r($which_db->save($r));
        return $r;
    }

    /**
     * TODO
     * moves assets from bucket to local
     * @param type $local
     */
    public static function _load_from_db_save_to_local($local, $bucket) {
        
    }

}