
<?php

// uncomment the following to define a path alias
//Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../modules/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return array(
    'aliases' => array(
        'bootstrap' => dirname(__FILE__) . '/../modules/bootstrap',
        'application_theme' => dirname(__FILE__) . '/../../themes/bootstrap',
        'ext' => 'application.extensions',
    ),
    'defaultController' => 'site',
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'homeUrl' => array(
        '/site/login'
    ),
    'defaultController' => 'site/login',
    'name' => 'AT Comm Workbench',
    'theme' => 'bootstrap',
    // requires you to copy the theme under your themes directory
// preloading 'log' component
    'preload' => array(
        'log',
        'bootstrap'
    ),
    // autoloading model and component classes
    'import' => array(
        'bootstrap.*',
        'bootstrap.components.*',
        'bootstrap.models.*',
        'bootstrap.controllers.*',
        'bootstrap.helpers.*',
        'bootstrap.widgets.*',
        'bootstrap.extensions.*',
        'application.models.*',
        'application.components.*',
        'application.filters.*',
        'application.extensions.MongoYii.*',
        'application.extensions.MongoYii.validators.*',
        'application.extensions.MongoYii.behaviors.*',
        'application.extensions.MongoYii.util.*',
        'ext.*',
    ),
    'modules' => array(
        'bootstrap' => array(
            'class' => 'bootstrap.BootStrapModule'
        ),
        'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii'
            ),
            'class' => 'system.gii.GiiModule',
            'password' => 'yaa',
            'ipFilters' => array(
                '127.0.0.1',
                '::1'
            ),
        ),
    ),
    // application components
    'components' => array(
        'curl' => array(
            'class' => 'ext.curl.Curl',
        ),
        'session' => array
            (
            'class' => 'HttpSession'
        ),
        'user' => array(
// enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ),
        'bsHtml' => array(
            'class' => 'bootstrap.components.BSHtml'
        ),
        // uncomment the following to enable URLs in path-format
        /**/
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                'api/sync/<which_service:[\w]+>/<push_or_pull:[\w]+>' => 'api/sync',
                ///
                'api/bucketdir/<which_service:[\w]+>' => 'api/bucketdir',
                'api/bucketdir/<which_service:[\w]+>/<which_type:[\w]+>' => 'api/bucketdir',
                ///
                'api/dbstructure/<which_service:[\w]+>' => 'api/dbstructure',
                ///
                'api/filestructure/<which_service:[\w]+>' => 'api/filestructure',
                'api/filestructure/<which_service:[\w]+>/<subdirectory:[\w\-]+>' => 'api/filestructure',
                'api/filestructure/<which_service:[\w]+>/<subdirectory:[\w\-]+>/<bottomdirectory:[\w\-]+>' => 'api/filestructure',
                ///
                'api/bucketfiles/<which_service:[\w]+>' => 'api/bucketfiles',
                ///
                'api/getdir/<which_service:[\w]+>' => 'api/getdir',
                'api/getdir/<which_service:[\w]+>/<image_name:[\w\-\ \.]+>' => 'api/getdir',
                ///
                'api/update/<which_service:[\w]+>/<load_or_save:[\w]+>' => 'api/update',
                ///
                'api/putimageinbucket/<which_service:[\w]+>' => 'api/putimageinbucket',
                'api/putimageinbucket/<which_service:[\w]+>/<image_name:[\w\-\ \.]+>' => 'api/putimageinbucket',
                ///
                'api/deleteimageinbucket/<which_service:[\w]+>' => 'api/deleteimageinbucket',
                'api/deleteimageinbucket/<which_service:[\w]+>/<image_name:[\w\-\ \.\S]+>' => 'api/deleteimageinbucket',
                ///
                'api/addimage/<which_service:[\w]+>/<image_name:[\w\-\ \.\S]+>' => 'api/addimage',
                ///
                'api/removeimage/<which_service:[\w]+>/<image_name:[\w\-\ \.\S]+>' => 'api/removeimage',
                ///
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // uncomment the following to use a MySQL database
        /*
          'db' => array(
          'connectionString' => 'mysql:host=localhost;dbname=pos',
          'emulatePrepare' => true,
          'username' => 'posuser',
          'password' => 'P0S_u$s3r',
          'charset' => 'utf8',
          ),
         */
        'mongodb' => array(
            'class' => 'EMongoClient',
            'server' => 'mongodb://localhost:27017',
            'db' => 'super_test'
        ),
        'errorHandler' => array(
// use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
// using Yii::app()->params['paramName']
    'params' => array(
// this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'allowips'=>array('22.150.133.177'),
    ),
);
