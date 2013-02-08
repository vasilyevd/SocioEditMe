<?php
// uncomment the following to define a path alias
Yii::setPathOfAlias('global',realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../'));
Yii::setPathOfAlias('common',Yii::getPathOfAlias('global.common.socio'));
Yii::setPathOfAlias('gext',Yii::getPathOfAlias('global.yii.extensions'));
Yii::setPathOfAlias('mainproject',Yii::getPathOfAlias('global').'/dev.socinfo.net.ua');
Yii::setPathOfAlias('todoext',dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'extensions');

echo Yii::getPathOfAlias('ext');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

include ('Config_Modules.php');
include ('Config_DB.php');
include ('Config_Log.php');
include ('Config_User.php');
include ('Config_Cache.php');

return array(
	'extensionPath'=>Yii::getPathOfAlias('mainproject.protected.extensions'),
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Socio',
    'charset'=>'utf-8',

    // Application default language.
    'language' => 'ru',
    'sourceLanguage' => 'en',

    // preloading 'log' component
    'preload'=>array(
        'log',
        // Twitter bootstrap setup.
        'bootstrap',
    ),

    // autoloading model and component classes
    'import'=>array(
	      //'common.models.*',
	      //'gext.widgets.*',
	      'mainproject.protected.models.*',

        'application.models.*',
        'application.models.dostup.*',
        'application.components.*',
        'application.controllers.*',
        'application.extensions.*',
        'application.extensions.widgets.*',
        'application.helpers.*',

        'ext.yii-mail.YiiMailMessage',
        'ext.yiiext.filters.setReturnUrl.ESetReturnUrlFilter',
    ),

        'modules'=>$conf_modules,

    // application components
    'components'=>array(
        'user' => $component_user,
        'db' => $conf_db,
				'db2'=>$conf_db2,
        'log' => $conf_log,
        'cache' => $conf_cache,

        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
                'urlFormat'=>'path',
                'showScriptName' => false,
                'rules'=>array(
                    '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                ),
       ),

        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),

        // Twitter bootstrap setup.
        'bootstrap'=>array(
            'class'=>'ext.bootstrap.components.Bootstrap',
            'responsiveCss'=>true,
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'admin@socinfo.net.ua',
	      'dbname'=>'socio',
	      'db2name'=>'todo',
    ),
);
