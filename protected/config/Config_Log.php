<?php

$conf_log=array(
'class'=>'CLogRouter',
            'routes'=>array(

                array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages

                /*
                array(
                        'class'=>'CWebLogRoute',
                        //'categories' => 'application, system.db.CDbCommand',
                        //'levels'=>'error, warning, trace, profile, info',
                        //'showInFireBug' => true,
                ),
                */

                /*
                array(
                    'class'=>'ext.db_profiler.DbProfileLogRoute',
                    'countLimit' => 1, // How many times the same query should be executed to be considered inefficient
                    'slowQueryMin' => 0.01, // Minimum time for the query to be slow
                    //'levels'=>'error, warning, trace, profile, info',
                ),
                */

                /*
                'profile' => array(
                    'enabled'=>true,
                    'class' => 'CProfileLogRoute',
                    //'levels' => 'profile',
                ),
                */

            ),
        );
?>
