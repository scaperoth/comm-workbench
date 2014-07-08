<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ApiHelper extends CHtml {
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */

    Const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';

    const LOGIN_ERROR = "You have insufficient permissions to continue";

    /**
     * 
     * @param type $push_or_pull
     * @param type $source
     * @param type $dest
     * @return mixed array
     * @throws CHttpException
     */
    public static function _ProcessSync($push_or_pull, $source, $dest) {


        if ($push_or_pull) {
            switch ($push_or_pull) {
                case 'push':
                    $JSON_array[] = self::_sync_from_source($dest, $source);
                    break;
                case 'pull':
                    $JSON_array[] = self::_sync_from_source($source, $dest);
                    break;

                default:
                    throw new CHttpException(404, "The API for 'api/syncfiles/$pushpull' cannot be found.");
            }
        }
        else
            throw new CHttpException(404, "The page you are looking for does not exist.");

        return $JSON_array;
    }

    /**
     * abstracts the delete and copy functions
     * returns array of the files in the destination directory
     * @param type $source
     * @param type $dest
     * @return type
     */
    public static function _sync_from_source($source, $dest) {
        $destination_array = array();
        $counter = 0;

//first check to see if there's anything in the local fs that doesn't belong
        self::_delete_from_dest($source, $dest);

        //now copy over all systems that do belong from source fs
        self::_copy_from_source($source, $dest);
        $destination_array['destination folder']=$dest;
        $destination_array['contents'] = self::_ReadFolderDirectory($dest);
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
     */
    public static function _delete_files($target) {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                self::_delete_files($file);
            }
            if (file_exists($target))
                rmdir($target);
        } elseif (is_file($target)) {
            unlink($target);
        }
    }

    /**
     * tranlsates folder directory into json array
     * from http://stackoverflow.com/questions/4987551/parse-directory-structure-strings-to-json-using-php
     * @param type $dir
     * @param type $listDir
     * @return type
     */
    public static function _ReadFolderDirectory($dir, $listDir = array()) {
        $listDir = array();
        if ($handler = opendir($dir)) {
            while (($sub = readdir($handler)) !== FALSE) {
                if ($sub != "." && $sub != ".." && $sub != "Thumb.db") {
                    if (is_file($dir . "/" . $sub)) {
                        $listDir[] = $sub;
                    } elseif (is_dir($dir . "/" . $sub)) {
                        $listDir[$sub] = self::_ReadFolderDirectory($dir . "/" . $sub);
                    }
                }
            }
            closedir($handler);
        }
        return $listDir;
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
    <title>' . $status . ' ' . self::_getStatusCodeMessage($status) . '</title>
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
