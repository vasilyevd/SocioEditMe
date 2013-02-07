<?php

/*

$component_user=array(
            'allowAutoLogin'=>true,
            'class' => 'RWebUser',
        );

*/


$component_user=array(
            'allowAutoLogin'=>true,
            'class' => 'application.modules.person.components.WebUserPerson',
            // 'loginUrl' => array('//person/person/login'),
        );


?>
