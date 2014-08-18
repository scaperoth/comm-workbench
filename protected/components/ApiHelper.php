<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ApiHelper extends CHtml {
// Members
    //sets whether or not in production
    const LIVE = false;
    //relative path. what you see is what you get.
    const GADGETS_SHARED = '/data/gadgets/backup/';
    //const GADGETS_SHARED = '/shared/Test/';
    const GADGETS_LOCAL = '/data/gadgets/filesystem/';
    const GADGETS_BUCKET = "/assets/images/gadget_images/";
    const WEPA_SHARED = '/data/wepa/backup/';
    const WEPA_LOCAL = '/data/wepa/filesystem/';
    const WEPA_BUCKET = "/assets/images/wepa_images/production_images";
    const WEPA_OUTAGE_BUCKET = "/assets/images/wepa_images/outage_images";
    Const TOPHOLDER = 'structure';
    Const APPLICATION_ID = 'ASCCPE';
    

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';

    const LOGIN_ERROR = "You have insufficient permissions to continue";

    /* #############################################
     * MISC
     * ############################################# */

    /**
     * appends full path to relative path constants
     * path here is expected to be ../protected/components/ for non bucket dir
     * path for bucket directory will be comm-workbench/themes/assets/bootstrap/
     * @param type $relative_path path to append full path to
     * @param type $bucket whether or not path is for image bucket dir
     */
    private function _create_full_path($relative_path, $bucket = false) {
        if ($bucket)
            return Yii::app()->theme->baseUrl . $relative_path;
        else
            return Yii::app()->basePath . $relative_path;
    }

    /* #############################################
     * File manipulation
     * COULD BE OPTIMIZED
     * ############################################# */

    /**
     * shrinks large directory name to only relevant data
     * @param type $dirname
     * @param type $root
     * @return type
     */
    public static function _trim_directory($dirname, $root) {
        if ($dirname . "/" == $root) {
            $dirname = 'GWU';
        } else {
            $dirname = substr_replace($dirname, '', 0, strlen($root));
        }
        return $dirname;
    }

    /**
     * @deprecated since version 1
     * tranlsates folder directory into json array
     * from http://stackoverflow.com/questions/4987551/parse-directory-structure-strings-to-json-using-php
     * @param type $dir directory to start search from
     * @param type $listDir array the append directory to
     * @return type
     */
    public static function _ReadFolderDirectory_from_local($dir, $listDir = array()) {
        $listDir = array();
        if ($handler = opendir($dir)) {
            while (($sub = readdir($handler)) !== FALSE) {
                if ($sub != "." && $sub != ".." && $sub != "Thumb.db") {
                    if (is_file($dir . "/" . $sub)) {
                        $listDir[] = $sub;
                    } elseif (is_dir($dir . "/" . $sub)) {
                        $listDir[$sub] = self::_ReadFolderDirectory_from_local($dir . "/" . $sub);
                    }
                }
            }
            closedir($handler);
        }
        if (empty($listDir)) {
            return "No database connection";
        }
        return $listDir;
    }

    /**
     * returns flat array of directories in specified level
     * @param type $filestructure
     * @param type $rootfolder
     * @param type $subfolder
     * @param type $bottomfolder
     * @return type
     */
    public static function _ReadFolder_subdirectory($service, $subfolder = '', $bottomfolder = '', $rootfolder = 'files') {
        $filestructure = self::_get_db_structure($service);
        if (empty($subfolder)) {
            $new_array = $filestructure[$rootfolder]['root'];
        } else if (empty($bottomfolder)) {
            $new_array = $filestructure[$rootfolder][$subfolder]['subfolder'];
        } else {
            $new_array = $filestructure[$rootfolder][$subfolder][$bottomfolder]['bottomfolder'];
        }
        array_multisort(array_values($new_array), SORT_DESC, array_keys($new_array), SORT_ASC, $new_array);
        return $new_array;
    }

    /**
     * 
     * @param type $sort_array
     * @return type
     */
    public static function _sorter($sort_array) {
        
    }

    /* #############################################
     * Image manipulation
     * ############################################# */

    /**
     * returns every parent of all images 
     * could be optimized!!!!
     * @param type $image_name
     * @param type $root
     */
    public static function _find_all_image_parent($root, $bucket) {
        $image_details = array();
        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($bucket, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if (!$item->isDir()) {
                $file = $iterator->current();
                $file = pathinfo($file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename());
                $folder = self::_find_image_parent($file['basename'], $root);
                $image_details[$file['basename']] = self::_find_image_parent($file['basename'], $root);
            }
        }

        return $image_details;
    }

    /**
     * parent of image
     * if the image is in multiple locations, it returns all parents
     * @param type $image_name
     * @param type $root
     */
    public static function _find_image_parent($image_name, $root) {
        $all_parents = array(
            'name' => $image_name,
            'location' => array()
        );

        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if (!$item->isDir()) {
                $file = $iterator->current();
                $file = pathinfo($file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename());
                if ($file['basename'] == $image_name) {
                    $all_parents['location'][] = self::_trim_directory($file['dirname'], $root);
                }
            }
        }

        return $all_parents;
    }

    /* #############################################
     * Bucket manipulation
     * ############################################# */

    public static function _get_bucket_files($which_db, $assoc_array = true) {
        $db = self::_ReadFolderDirectory_from_db(Yii::app()->mongodb->$which_db);
        return $db['bucket'];
    }

    /* #############################################
     * Database manipulation
     * ############################################# */

    /**
     * tranlsates folder directory into json array
     * from http://stackoverflow.com/questions/4987551/parse-directory-structure-strings-to-json-using-php
     * @param type $dir directory to start search from
     * @param type $listDir array the append directory to
     * @return type
     */
    public static function _ReadFolderDirectory_from_db($which_db) {
        $db_to_array = array();

        $cursor = $which_db->find()->sort(array('timestamp' => -1))->limit(1);

        foreach ($cursor as $doc) {

            $db_to_array = $doc;
        }
        return $db_to_array;
    }

    /* #####################################
     * GETTERS 
     * #################################### */

    /**
     * returns which service is being selected
     * is located here instead of the helper in order to use the
     * local variables for file names and databases
     * @param type $service name of service to get data from
     * @return type
     */
    public function _get_service($param) {
        $service_details = array();
        switch ($param) {
            case 'gadgets':
                $service_details['local_file'] = self::_create_full_path(self::GADGETS_LOCAL);
                $service_details['shared_file'] = (self::LIVE?self::_create_full_path(self::GADGETS_SHARED):self::GADGETS_SHARED);
                $service_details['bucket'] = self::_create_full_path(self::GADGETS_BUCKET, true);
                $service_details['database'] = Yii::app()->mongodb->gadgets;
                break;
            case 'wepa':
                $service_details['local_file'] = self::_create_full_path(self::WEPA_LOCAL);
                $service_details['shared_file'] = self::_create_full_path(self::WEPA_SHARED);
                $service_details['bucket'] = self::_create_full_path(self::WEPA_BUCKET, true);
                $service_details['outage_bucket'] = self::_create_full_path(self::WEPA_OUTAGE_BUCKET, true);
                $service_details['database'] = Yii::app()->mongodb->wepa;

                break;
        }


        return $service_details;
    }

    public static function _get_bucket_url($which_service, $assoc_array = true) {
        $service = self::_get_service($which_service);
        $bucket = $service['bucket'];
        return $bucket;
    }

    public static function _get_local_path($which_service, $assoc_array = true) {
        $service = self::_get_service($which_service);
        $bucket = $service['local_file'];
        return $bucket;
    }

    public static function _get_db_structure($which_service, $assoc_array = true) {
        $service = self::_get_service($which_service);
        $db = self::_ReadFolderDirectory_from_db($service['database']);
        return $db;
    }

    /**
     * 
     * @param type $status
     * @param string $body
     * @param type $content_type
     */
    public static function _sendResponse($status = 200, $body = '', $content_type = 'text/html') {
// set the status
        header("Access-Control-Allow-Origin: *");
        $status_header = 'HTTP/1.1 ' . $status . ' ' . self::_getStatusCodeMessage($status);
        header($status_header);
// and the content type
        header('Content-type: ' . $content_type);

// pages with body are easy
        if ($body != '') {
// send the body
            echo $body;
        }
// we need to create the body if none is passed
        else {
// create some body messages
            $message = '';

// this is purely optional, but makes the pages a little nicer to read
// for your users.  Since you won't likely send a lot of different status codes,
// this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

// servers don't always have a signature turned on 
// (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

// this should be templated in a real-world solution
            $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>'
                    . $status . ' ' . self::_getStatusCodeMessage($status) . '</title>
    </head>
    <body>
        <h1>' . self::_getStatusCodeMessage($status) . '</h1>
        <p>' . $message . '</p>
        <hr />
        <address>' . $signature . '</address>
    </body>
</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    public static function _getStatusCodeMessage($status) {
// these could be stored in a .ini file and loaded
// via parse_ini_file()... however, this will suffice
// for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

}

?>
