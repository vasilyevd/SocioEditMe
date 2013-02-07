<?php
$conf_modules=array(

    'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'reverse',
            'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths'=>array(
                'bootstrap.gii',
            ),
    ),

    'person'=>array(),

    );
?>
