<?php
class AuthController extends Controller {
	public $layout='//layouts/main';

	public $defaultAction = 'login';
	
	private $_identity;
	public $_user;
	
	public function accessRules() {
		return array(
				array('allow',
					'actions'=>array('login', 'register'),
					'users'=>array('*'),
					),
				array('allow',
					'actions'=>array('logout'),
					'users'=>array('@'),
					),
				array('deny',  // deny all other users
					'users'=>array('*'),
					),
				);
	}

	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
			),
		);
	}

	public function actionLogin() 
	{
		if (!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->user->returnUrl);
		}
		
		$service = Yii::app()->request->getQuery('service');
		$this->_user=new PersonUser('login');
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
			echo CActiveForm::validate($this->_user);
			Yii::app()->end();
		}
		
		if (isset($service)) {
		}
		else {
			if (isset($_POST['PersonUser'])) {
				//echo "isset(_POST['PersonUser'])";
				$this->_user->attributes = $_POST['PersonUser'];
				$this->_user->username =$this->_user->email;
				
				if ($this->_user->validate()) {
				if ($this->_user->login('email')) {
					//echo "validate && login";
					//cookie with login type for later flow control in app
					
					if(Yii::app()->getModule('person')->afterLogin !== false) 
						call_user_func(Yii::app()->getModule('person')->afterLogin);

					$this->redirectUser($this->_user);
				}
				} 
			}				
		}
		
		// display the login form
		if (Yii::app()->request->isAjaxRequest || isset($_GET['_isAjax']))
			$this->renderPartial('/person/login',array('model'=>$this->_user));
		else
			$this->render('/person/login',array('model'=>$this->_user));
			
	}

	public function actionRegister()
	{
		if (!Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->user->returnUrl);

		$model=new PersonUser('registration');

		if(isset($_POST['PersonUser']))
		{
			$model->attributes=$_POST['PersonUser'];

			if ($model->validate() && !$model->hasErrors()) {
				if($model->save()) {
					$this->redirect(Yii::app()->baseUrl . '/person');
				}
			}
		}

		$this->render('/person/register',array(
				'model'=>$model,
			));
	}


	public function actionPassRequest()
	{
		$formmodel = new PersonUser('passRequest');

		if (isset($_POST['PersonUser'])) {
			$formmodel->attributes = $_POST['PersonUser'];
			$this->performAjaxValidation($formmodel);

			$attr='email';
			$val=$formmodel->email;

			if ($formmodel->validate() && $formmodel->email) {
				$model = PersonUser::model()->findByAttributes(array($attr=>$val));
				if ($model) {
					$model->scenario = 'passRequest';
					// сохраняем чтобы установить активационный код
					if ($model->save()) {
						// отправляем письмо
						$mail = new UGMail($model, UGMail::PASS_RESET);
						if ($mail->send()) {
							if(!Yii::app()->user->hasFlash('success')) {
								Yii::app()->user->setFlash('success', Yii::t('PersonModule.general','An email containing the instructions to reset your password has been sent to your email address: {email}'));
							}
							$this->redirect(Array ('/person/person/Recovery'));
						}

					} else {
						Yii::app()->user->setFlash('error', Yii::t('PersonModule.general','An Error Occurred. Please try later.'));
					}
					// выходим на всякий случай))
					// Yii::app()->user->logout();	// сука убивает сообщения
					// перенаправляем настраницу поле отправки письма. это может быть и страница запроса кода присланого.
					$this->redirect(Array ('/person/person/PassRequest'));
				} else {
					$formmodel->addError('all','An Error Occurred. Please try later.');
				}
			}
		}
		// если нет POST или GET - форма
		$this->render('passRequest', array('model'=>$formmodel));
	}

	
	public function redirectUser($user) 
	{
		// $user->lastvisit = time();
		// $user->save(true, array('lastvisit'));

		Yii::app()->user->setState('first_login', true);
		if(isset($_POST) && isset($_POST['returnUrl']))
			$this->redirect(array($_POST['returnUrl']));

		if(isset(Yii::app()->user->returnUrl))
			$this->redirect(Yii::app()->user->returnUrl);

		if (Yii::app()->getModule('person')->returnUrl !== '')
			$this->redirect(Yii::app()->getModule('person')->returnUrl);
		else
			$this->redirect(Yii::app()->user->returnUrl);
	}



}
