<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Console',

    // preloading 'log' component
    'preload'=>array('log'),

    'import'=>array(
        'application.components.*',
        'application.models.*',
    ),

    // application components
    'components'=>array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=socio',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'reverse',
            'tablePrefix'=>'',
            'charset' => 'utf8',

            'autoConnect'=>false,

            'enableProfiling'=>true,
            'enableParamLogging' => true,

            'schemaCachingDuration'=>360,
            'queryCacheID'=>'cache'
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),
    ),
);
