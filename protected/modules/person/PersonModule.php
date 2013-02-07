<?php
Yii::setPathOfAlias('PersonModule' , dirname(__FILE__));

Yii::import('PersonModule.models.*');
Yii::import('PersonModule.components.*');


class PersonModule extends CWebModule
{
	public $salt='111';
	
	public $enableCaptcha = true;
	
	// valid callback function that executes after user login
	public $afterLogin = false;
	
	/**
	 * specify the classes containing the mail messages
	 * @var array
	 */
	public $mailMessages = array();
	
	public $controllerMap=array(
		// 'default'=>array('class'=>'PersonModule.controllers.PDefaultController'),
		'auth'=>array('class'=>'PersonModule.controllers.AuthController'),
	);
	
	public function init() {
		parent::init();
		$this->defaultController = 'PDefault';
	}


}




