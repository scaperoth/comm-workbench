
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
    ) ,
    'defaultController' => 'site',
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'homeUrl' => array(
        '/'
    ) ,
    'name' => 'AT Comm Workbench',
    'theme' => 'bootstrap',
    
    // requires you to copy the theme under your themes directory
    // preloading 'log' component
    'preload' => array(
        'log',
        'bootstrap'
    ) ,
    
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
        'application.extensions.MongoYii.*',
        'application.extensions.MongoYii.validators.*',
        'application.extensions.MongoYii.behaviors.*',
        'application.extensions.MongoYii.util.*',
        'ext.*',
    ) ,
    'modules' => array(
        'bootstrap' => array(
            'class' => 'bootstrap.BootStrapModule'
        ) ,
        'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii'
            ) ,
            'class' => 'system.gii.GiiModule',
            'password' => 'yaa',
            'ipFilters' => array(
                '127.0.0.1',
                '::1'
            ) ,
        ) ,
    ) ,
    
    // application components
    'components' => array(
        'user' => array(
            
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ) ,
        'bsHtml' => array(
            'class' => 'bootstrap.components.BSHtml'
        ) ,
        
        // uncomment the following to enable URLs in path-format
        /**/
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ) ,
        ) ,
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
        'errorHandler' => array(
            
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ) ,
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ) ,
                
                // uncomment the following to show log messages on web pages
                /*
                array(
                'class'=>'CWebLogRoute',
                ),
                */
            ) ,
        ) ,
    ) ,
    
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ) ,
);
