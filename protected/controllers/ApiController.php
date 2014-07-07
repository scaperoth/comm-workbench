<?php

class ApiController extends Controller {

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
     * code pulled from http://stackoverflow.com/questions/5707806/recursive-copy-of-directory
     */
    public function actionSyncfiles() {
        ////fbwnas11/Public/
        $source = Yii::app()->basePath . '/data/backup/';
        $dest = Yii::app()->basePath . '/data/filesystem/';

        foreach (
        $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dest, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                $this->delete_files($item);
            }
        }


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

    /*
     * php delete function that deals with directories recursively
     */

    function delete_files($target) {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                $this->delete_files($file);
            }
            if (file_exists($target))
                rmdir($target);
        } elseif (is_file($target)) {
            unlink($target);
        }
    }

}