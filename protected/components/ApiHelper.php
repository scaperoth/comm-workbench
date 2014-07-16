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
        $destination_array['destination folder'] = $dest;
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
        } elseif (is_file($target)) {
            unlink($target);
        }
    }

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
     * returns every parent of image
     * @param type $image_name
     * @param type $root
     */
    public static function _find_image_parent($image_name, $root) {
        $all_parents = array();
        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if (!$item->isDir()) {
                $file = $iterator->current();
                $file = pathinfo($file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename());
                if ($file['basename'] == $image_name) {
                    $all_parents[] = self::_trim_directory($file['dirname'], $root);
                }
            }
        }

        return $all_parents;
    }

    /**
     * shrinks large directory name to only relevant data
     * @param type $dirname
     * @param type $root
     * @return type
     */
    public static function _trim_directory($dirname, $root) {

        if ($dirname . "\\" == $root) {
            $dirname = 'GWU';
        } else {
            $dirname = preg_replace('/^' . preg_quote($root, '/') . '/', '', $dirname);
        }
        return $dirname;
    }

    /**
     * tranlsates folder directory into json array
     * from http://stackoverflow.com/questions/4987551/parse-directory-structure-strings-to-json-using-php
     * @param type $dir directory to start search from
     * @param type $listDir array the append directory to
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
     * fills image bucket from source. 
     * bucket found in themes/bootstrap/images/gadget_images/
     * @param type $source
     * @param type $bucket
     */
    public static function _fill_bucket($source, $bucket) {
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
                $file = pathinfo($file->getPath() . "\\" . $file->getFilename());
                $ext = $file['extension'];
                if (in_array($ext, $supported_images) && !in_array($file['basename'], $bucket_files)) {
//echo 'Image found!</br>';
                    $bucket_files[] = $file['basename'];
                    copy($item, $bucket . DIRECTORY_SEPARATOR . $file['basename']);
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
    public static function _add_image_to_bucket($bucket, $file) {
        $bucket_files = array(
        );

        $allowedExts = array("gif", "jpeg", "jpg", "png");

        $temp = explode(".", $file["name"]);
        $extension = end($temp);

        if ($file["size"] < 20000 && in_array($extension, $allowedExts)) {
            if ($file["error"] > 0) {
                $bucket_files["Return Code"] = $file["error"];
            } else {
                $bucket_files["Return Code"] = $file["name"];
                $bucket_files["Return Code"] = $file["type"];
                $bucket_files["Return Code"] = ($file["size"] / 1024) . " kB";
                $bucket_files["Return Code"] = $file["tmp_name"];
                if (file_exists("upload/" . $file["name"])) {
                    $bucket_files["Return Code"] = $file["name"];
                } else {
                    move_uploaded_file($file["tmp_name"], $bucket . $file["name"]);
                    $bucket_files["Return Code"] = "Stored in: " . $bucket . $file["name"];
                }
            }
        } else {
            echo "Invalid file";
        }
        
        $bucket_files['files'] = self::_ReadFolderDirectory($bucket);

        return $bucket_files;
    }

    /**
     * adds a specific image to the bucket
     * @param type $source
     * @param type $bucket
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
     * Save and UPdate Functions
     * ############################################# */

    /**
     * translates the file system into a mongo db
     * @param type $local
     */
    public static function _save_to_db_load_from_local($local, $which_db) {

        $which_db->remove();

        $r = array(
            "timestamp" => date('m-d-y h:i:s'),
            "files" => array(),
        );
        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($local, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->getFilename() != 'Thumbs.db' && $item->getFilename() != 'Block.txt') {
                $path = $item->isDir() ? array($item->getFilename() => array()) : array("images" => $item->getFilename());

                for ($depth = $iterator->getDepth() - 1; $depth >= 0; $depth--) {
                    $path = array($iterator->getSubIterator($depth)->current()->getFilename() => $path);
                }
                $r["files"] = array_merge_recursive($r["files"], $path);
            }
        }

        array_multisort(array_keys($r), SORT_STRING, $r);
        $which_db->save($r);

        return $r;
    }

    /**
     * TODO
     * moves assets from bucket to local
     * @param type $local
     */
    public static function _load_from_db_save_to_local($local, $bucket) {
        
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
