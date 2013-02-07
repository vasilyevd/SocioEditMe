<?php
class PersonUser extends CActiveRecord
{
	const ROOT = 1; // id of root user
	
	// urers statuses
	const BANNED = 0; // забанет
	const ACTIVE = 1; // активен 4
	const WAITING_ACTIVATION = 2; // - ждет активации 1
	const WAITING_APPROVAL = 3; // ждет подтверждения активации 2
	const PASSWORD_CHANGE_REQUEST = 6; // запрошен новый пароль 3
		const REMOVED = 9; // удален

	public $rememberMe; 
	public $captcha;
	public $password_confirm; // поле проверки проверочного пароля
	public $old_password; // поле старого пароля при смене пароля
	
	private $_identity;
	
	public $_options=array(
		'user_need_activation'=>false,
		'user_need_approval'=>false,
	); // данная опция в дальнейшем может быть вынесена в настройки приложения
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return Yii::app()->db->tablePrefix.'user_persons';
	}
	
	public function rules()
	{
		Yii::import('person.validation.*');
		$rules = array(
			array('username, password, last_name,second_name, name', 'length', 'max'=>120),
			array('email', 'email'),
			array('rememberMe, params', 'safe'),
			
			// rules for passRequest
			// array('username, email','required','on'=>'passRequest'),
			array('email', 'checkMail', 'on'=>'passRequest'),
			// array('answer', 'securityQuestion', 'on'=>'passRequest'),
			// rules for mailRequest
			// array('mail','requestableMail','on'=>'mailRequest'),
			// rules for changePassword
			array('old_password', 'required', 'on' =>'changePassword'),
			array('old_password', 'oldPassMatch', 'on' =>'changePassword'),
			// rules for admin
			// array('group', 'levelCheck', 'on' => 'admin'),
			// rules for multiple scenarios
			array('username', 'required', 'on' => array('registration')),
			array('email, old_password, password, password_confirm', 'accountOwnership', 'on'=>array('update', 'changePassword')),
			// array('username', 'length', 'min'=>4, 'on'=>array('changePassword')),
			array('email', 'required', 'on'=>array('registration','admin','mailRequest','update','invitation')),
			array('username, email', 'unique', 'on'=>array('registration','admin', 'recovery','update', 'invitation')),
			array('username', 'match', 'pattern'=>'/^[A-Za-z0-9-_\-]{3,}$/', 'on'=>array('registration','admin','recovery'),
				'message' => 'Имя пользователя может состоять из латинских букв и символов "-" и "_"'),
			array('password', 'required', 'on'=>array('recovery','changePassword')),
			array('password', 'passwordStrength', 'on'=>array('registration','admin','recovery','changePassword')),
			array('password_confirm', 'required', 'on'=>array('registration', 'recovery','changePassword')),
			array('password_confirm', 'compare', 'compareAttribute' => 'password','on'=>array('changePassword','recovery', 'registration'),
				'message' => 'Пароли не совпадают'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, status, name, second_name, last_name', 'safe', 'on'=>'search'),
		
		);
		if(Yii::app()->getModule('person')->enableCaptcha) {
			// rules for registration	
			$rules[] = array('captcha', 'required', 'on' => 'registration');
			$rules[] = array('captcha', 'captcha', 'on' => 'registration');
		}
		$rules[] = array($this->LoginField.', password', 'required', 'on' => array('login', 'registration'));
		// rules for activation
			$rules[] =array($this->LoginField.', activation_code','required','on'=>'activate');
		// $rules[]=array('activation_code','checkCode','on'=>'activate'),


		return $rules;
	}	
	
		public function relations()
	{
		$relations = array(
			'profile'=>array(self::HAS_ONE, 'PersonMetrics', 'user_id')
		);
		return $relations;
	}
	
		public function attributeLabels()
	{
		return array(
			'id' => 'ID пользователя',
			'username' => 'Логин',
			'password' => 'Пароль',
			'email' => 'E-mail',
			'name' => 'Имя',
			'second_name'=>'Отчество',
			'last_name' => 'Фамилия',
			'status' => 'статус',
			'question' => 'Вопрос',
			'answer' => 'Ответ',
			'creation_date' => 'дата создания',
			'password_confirm' => 'Подтверждение пароля',
			'old_password' => 'Старый пароль',
			'access' => 'Доступ',
			'captcha' => 'Введите слово на картинке',
			'rememberMe' => 'Запомнить меня на этом компьютере',
			'params'=>'Другим пользователям :',
			'activation_code'=>'Код подтверждения',
		);
	}
	
	public function checkCode($attribute,$params)
	{
		$user = self::model()->findByAttributes(array('username'=>$this->username));
		if (empty($user))
			$this->addError('username', Yii::t('PersonModule.recovery','Username not valid'));
		else if ((int)$user->status !== self::WAITING_ACTIVATION && (int)$user->status !== self::PASSWORD_CHANGE_REQUEST && (int)$user->status !== self::ACTIVE)
			$this->addError('username', Yii::t('PersonModule.recovery','Username not valid'));
		else if ($user->activation_code !== $this->activation_code)
			$this->addError('activation_code', Yii::t('PersonModule.recovery','Invalid activation code'));
	}
	
	public function checkMail($attribute, $params)
	{
		$user = self::model()->findByAttributes(array('email'=>$this->email));
		if (empty($user) && $this->username=='')
			$this->addError('email', Yii::t('PersonModule.recovery','Invalid email address'));
		else if ((int)$user->status !== self::ACTIVE)
			$this->addError('username', Yii::t('PersonModule.recovery','Username not valid'));
	}
	
	public function accountOwnership($attribute,$params)
	{
		if (Yii::app()->user->checkAccess('updateAllPersonProfiles'))
			return true;
		else if ($this->id !== Yii::app()->user->id)
			$this->addError($attribute, Yii::t('PersonModule.general','You are not allowed to update other accounts'));
	}

	public function oldPassMatch($attribute,$params)
	{		
		// check if you have user admin permission, in that case this validation will
		// be skipped, otherwise will check if you are trying to update your own account
		
		if (Yii::app()->user->checkAccess('updateAllPersonProfiles'))
			return true;
		// load the user model and check if the old password match
		$user = self::model()->findByPk($this->id);

		
			$user_password= $this->hashPass($this->old_password); 
			$db_password = $user->password;
		
		if ($db_password !== $user_password)
			$this->addError('old_password', Yii::t('UserGroupsModule.general','You didn\'t enter the correct password'));
	}
		
	public function login($mode = 'regular')
	{
		if($this->_identity===null)
		{
			if ($mode === 'email') {
				$this->_identity=new PersonIdentity($this->email,$this->password);
				$this->_identity->authenticate();
			}  else if ($mode === 'fromHash') {
				$this->_identity=new PersonIdentity($this->e,'',$this->password);
				$this->_identity->authenticate();
			} else if ($mode === 'recovery') {
				$this->_identity=new PersonIdentity($this->email,$this->activation_code);
				$this->_identity->recovery();
			} else if ($mode === 'activate') {
				$this->_identity=new PersonIdentity($this->email,$this->activation_code);
				$this->_identity->recovery('activate');
			}
		}
		
		if($this->_identity->errorCode===PersonIdentity::ERROR_NONE)
		{			
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_USERNAME_INVALID)
			$this->addError('username',Yii::t('PersonModule.recovery','Username not valid'));
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_PASSWORD_INVALID)
			$this->addError('password',Yii::t('PersonModule.general','You didn\'t enter the correct password'));
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_USER_BANNED)
			$this->addError('username',Yii::t('PersonModule.general','We are sorry, but your account is banned'));
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_USER_INACTIVE)
			$this->addError('username',Yii::t('PersonModule.general','Account not active').'<br/>'.CHtml::link(Yii::t('PersonModule.general','Activate the account'), array('/person/person/activate')));
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_USER_APPROVAL)
			$this->addError('username',Yii::t('PersonModule.general','This account is not approved yet'));
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_PASSWORD_REQUESTED)
			$this->addError('password',Yii::t('PersonModule.general','A password change has been requested.<br/>You won\'t be able to login until you change the password.'));
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_USER_REMOVED)
			$this->addError('username',Yii::t('PersonModule.general','This user removed.'));
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_ACTIVATION_CODE)
			$this->addError('activation_code',Yii::t('PersonModule.recovery','Invalid activation code'));
		else if ($this->_identity->errorCode === PersonIdentity::ERROR_USER_ACTIVE)
			$this->addError('activation_code',Yii::t('PersonModule.recovery','This user cannot login in recovery mode.'));
		else
			$this->addError('password',Yii::t('PersonModule.recovery','wrong user or password.').'<br/>'.CHtml::link(Yii::t('PersonModule.recovery', 'Password Recovery'), array('/Person/Person/passRequest')));
			return false;
	}

	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			//$this->params=serialize($this->params);
			
			if ($this->isNewRecord && $this->scenario != 'import')
				$this->creation_date = date('Y-m-d H:i:s');
			
			// если создается админом
			if ($this->scenario === 'admin' && $this->isNewRecord && (empty($this->password) || empty($this->email))) {
				$this->status = self::WAITING_ACTIVATION;
				$this->activation_code = $this->generateActivationKey(false);
				$this->activation_time = date('Y-m-d H:i:s');
				if (empty($this->email))
					$this->username = uniqid('_user');
			} else if (($this->scenario === 'admin' && $this->isNewRecord) || $this->scenario === 'recovery' || $this->scenario === 'activate' || $this->scenario === 'swift_recovery') {
				// sets the right status based on configurations
				if ((int)$this->status === self::WAITING_ACTIVATION && $this->_options['user_need_approval']===true
					&& ($this->scenario === 'recovery' || $this->scenario === 'swift_recovery'))
					$this->status = self::WAITING_APPROVAL;
				else
					$this->status = self::ACTIVE;
			}
			
			
			// if it's a new record generates a new password if a password was defined
			if (($this->isNewRecord || $this->scenario === 'recovery' || $this->scenario === 'changePassword') && !empty($this->password) && $this->scenario != 'import') {
				$this->password = $this->hashPass($this->password);
			}
			
			// sets the correct user status and group upon registration based on the configurations
			if ($this->scenario === 'registration') {
					if ($this->_options['user_need_activation']===true) {
						$this->status = self::WAITING_ACTIVATION;
						$this->activation_code = $this->generateActivationKey(false);
						$this->activation_time = date('Y-m-d H:i:s');
				} else if ($this->_options['user_need_approval']===true)
					$this->status = self::WAITING_APPROVAL;
				else
					$this->status = self::ACTIVE;
			}
			
			// удаляем из базы код активации если статус НЕ ждет активации, НЕ ждет подтверждения, НЕ сценарий запроса на востановление
			if ((int)$this->status !== self::WAITING_ACTIVATION && (int)$this->status !== self::WAITING_APPROVAL && (int)$this->status !== self::PASSWORD_CHANGE_REQUEST && $this->scenario !== 'passRequest')
				$this->activation_code = NULL;
			
			// при запросе востановления пароля - создаем новый код активации
			if ($this->scenario === 'passRequest') {		
				//$this->status = self::PASSWORD_CHANGE_REQUEST;
				//$this->password = NULL;
				//$this->is_bitrix_pass=0;
				$this->activation_code = $this->generateActivationKey(false);
				$this->activation_time = date('Y-m-d H:i:s');
			}
			return true;
		}
		return false;	
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		
		// send the needed emails for account activation
		if (($this->scenario === 'admin' || $this->scenario === 'registration') && $this->status === self::WAITING_ACTIVATION) {
			$mail = new UGMail($this, UGMail::ACTIVATION);
			$mail->send();
		} else {
				//$mail = new UGMail($this, UGMail::WELCOME);
				//$mail->send();
		}
		
		// set the flash messages
		if ($this->scenario === 'registration' || $this->scenario === 'recovery' || $this->scenario === 'swift_recovery') {
			if ((int)$this->status === self::WAITING_ACTIVATION)
				Yii::app()->user->setFlash('success', Yii::t('PersonModule.general','An email was sent with the instructions to activate your account to the address {email}.', array('{email}'=>$this->email)));
			else if ((int)$this->status === self::WAITING_APPROVAL)
				Yii::app()->user->setFlash('success', Yii::t('PersonModule.general','Registration Complete. You now have to wait for an admin to approve your account.'));
			else
				Yii::app()->user->setFlash('success', Yii::t('PersonModule.general','Registration Complete, you can now login.'));
		}
	}
	
	public function getSalt()
	{
		list($date, $time) = explode(' ', $this->creation_date);
		$date = explode('-', $date);
		$time = explode(':', $time);

		date_default_timezone_set('UTC');
    	$timestamp = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
		// create the salt
		$salt = $this->username . $timestamp;
		// add the additional salt if it's provided
		if (isset(Yii::app()->controller->module->salt))
			$salt .= Yii::app()->controller->module->salt;
		else {
			$modulesData = Yii::app()->getModules();
			$salt .= isset($modulesData['person']['salt'])?$modulesData['person']['salt']:'111';
		}	
		return $salt;
	}
	
		public function getFio()
	{
		$fio = $this->name." ".$this->last_name;
		
		return $fio;
	}

	
	public static function getLoginField()
	{
		return 'email';
	}
	
	public static function encrypt($string = "")
	{
		$salt = Yii::app()->getModule('person')->salt;
		$string = sprintf("%s%s%s", $salt, $string, $salt);
		return md5($string);
	}
	
	public static function hashPass($string = "")
	{
		$salt = Yii::app()->getModule('person')->salt;
		$string = sprintf("%s%s%s", $salt, $string, $salt);
		return md5($string);
	}
	
	public function generateActivationKey($activate = false)
	{
		$this->activation_code = $activate
			? uniqid()
			: PersonUser::encrypt(microtime() . $this->password);

		return $this->activation_code;
	}
	
	
	
	
	
}