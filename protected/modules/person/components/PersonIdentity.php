<?php
/**
 * UserGroupsIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class PersonIdentity extends CUserIdentity
{
	public $login; // is user email
	
	private $id;
	private $name;
	private $email;
		
	private $hash;
	
	const ERROR_NONE = 0;
	const ERROR_USERNAME_INVALID = 1;
	const ERROR_USER_INACTIVE = 2;
	const ERROR_PASSWORD_INVALID = 3;
	const ERROR_USER_APPROVAL = 5;
	const ERROR_USER_BANNED = 4;
	const ERROR_USER_REMOVED=9;
	const ERROR_PASSWORD_REQUESTED = 6;
	
	const ERROR_USER_ACTIVE = 7; // пользователь уже активен и не требует активации
	const ERROR_ACTIVATION_CODE = 8;

	
	
	
	
	const ERROR_STATUS_USER_DOES_NOT_EXIST=7;
	const ERROR_EMAIL_INVALID=10;
	const ERROR_NOT_AUTHENTICATED = 3;
	

	private $recovery;
	
			
	public function __construct($username,$password,$hash=null)
	{
		$this->login=$username;
		$this->password=$password;
		if ($hash) $this->hash=$hash;
	}
	
	public function authenticate()
	{
		$user = $this->UserByMode;
		if(!count($user)){
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		} else if((int)$user->status === PersonUser::WAITING_ACTIVATION) {
			$this->errorCode=self::ERROR_USER_INACTIVE;
		} else if(!$this->hash && (PersonUser::hashPass($this->password)!==$user->password) || $this->hash && ($user->password!=$this->hash)) {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		} else if((int)$user->status === PersonUser::WAITING_APPROVAL) {
			$this->errorCode=self::ERROR_USER_APPROVAL;
		} else if((int)$user->status === PersonUser::BANNED) {
			$this->errorCode=self::ERROR_USER_BANNED;
		} else if((int)$user->status == PersonUser::REMOVED) {
			$this->errorCode=self::ERROR_USER_REMOVED;
		} else if((int)$user->status === PersonUser::PASSWORD_CHANGE_REQUEST) {
			$this->errorCode=self::ERROR_PASSWORD_REQUESTED;
		} else {
			$this->errorCode=self::ERROR_NONE;
			$this->id = $user->id;
			$this->setState('id', $user->id);
			$this->name = $user->username;
			$this->email = $user->email;
			$this->recovery = false;
			$user->last_login = date('Y-m-d H:i:s');
			$user->save();
		}
		return !$this->errorCode;
	}
	
	/**
	 * login in recovery mode
	 * @return boolean wheter is possible to login in recovery mode
	 */
	public function recovery($mode=null)
	{
		$model=$this->UserByMode;
		if ($mode) $model->setScenario($mode);
		if(!count($model)) {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		} else if((int)$model->status === PersonUser::BANNED) {
			$this->errorCode=self::ERROR_USER_BANNED;
		 } else if((int)$model->status === PersonUser::ACTIVE) {
			$this->errorCode=self::ERROR_USER_ACTIVE;
		} else if($mode!='activate' && (int)$model->status === PersonUser::WAITING_APPROVAL) {
			$this->errorCode=self::ERROR_USER_APPROVAL;
		} else if($mode!='activate' && $model->activation_code !== $this->password) {
			$this->errorCode=self::ERROR_ACTIVATION_CODE;
			$this->id = $model->id;
		} else {
			$this->errorCode=self::ERROR_NONE;
			$this->id = $model->id;
			$this->name = Yii::t('PersonModule.general','Recovery Mode');
			if ($mode!='activate') {
				$this->recovery = true;
			}	else {
				$model->status=PersonUser::ACTIVE;
			}
			$model->last_login = date('Y-m-d H:i:s');
			$model->save();
		}
		return !$this->errorCode;
	}
	
	public function getId()
	{
		return $this->id;
	}
		
	/** ?? */	
	public function getRoles()
	{
		return $this->Role;
	}
		
	public function getName()
	{
		return $this->name;
	}
		
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getRecovery()
	{
		return $this->recovery;
	}
	
	public function getUserByMode()
	{
				return PersonUser::model()->find('LOWER(email)=:lemail', array(':lemail'=>strtolower($this->login)));
	}
}
		
