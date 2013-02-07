<?php
class WebUserPerson extends CWebUser
{
	public $loginUrl = array('/Person/');
	
	private $_data;
	
	/** TODO 
	 * сделать нормальный кеш данных пользователя не завязанный на ActiveRecords 
	 */
	public function data() {
		if($this->_data instanceof PersonUser)
			return $this->_data;
		else if($this->id && $this->_data = PersonUser::model()->cache(0)->findByPk($this->id))
			return $this->_data;
		else
			return new PersonUser();
	}
	
	// переписываем свой метод входа
	public function login($identity,$duration=0)
	{
	    $id=$identity->getId();	 
	    $states=$identity->getPersistentStates();	   
	    if($this->beforeLogin($id,$states,false))
	    {	    	
	        $this->PChangeIdentity($id,$identity->getName(),$identity->getRecovery(), $states);

	        if($duration>0)
	        {
	            if($this->allowAutoLogin)
	                $this->saveToCookie($duration);
	            else
	                throw new CException(Yii::t('UserGroupsModule.admin','{class}.allowAutoLogin must be set true in order to use cookie-based authentication.',
	                    array('{class}'=>get_class($this))));
	        }

	        $this->afterLogin(false);
	    }
	}
	
	/**
	 * updates the identity of the user
	 * @param string $id
	 * @param string $name
	 * @param int $group
	 * @param string $groupName
	 * @param int $level
	 * @param array $accessRules
	 * @param string $home
	 * @param bool $recovery
	 * @param mixed $states
	 * @see CWebUser::changeIdentity()
	 */
	protected function PChangeIdentity($id,$name,$recovery, $states)
	{
	    $this->setId($id);
	    $this->setName($name);
	    $this->loadIdentityStates($states);
			$this->setRecovery($recovery);
	}
	
	protected function saveToCookie($duration)
	{
		$app=Yii::app();
		$cookie=$this->createIdentityCookie($this->getStateKeyPrefix());
		$cookie->expire=time()+$duration;
		$data=array(
			$this->getId(),
			$this->getName(),
			$duration,
			$this->saveIdentityStates(),
		);
		$cookie->value=$app->getSecurityManager()->hashData(serialize($data));
		$app->getRequest()->getCookies()->add($cookie->name,$cookie);
	}
	
	public function getEmail()
	{
		return $this->data()->email;
	}
	
	public function setRecovery($value)
	{
		$this->setState('__recovery',$value);
	}

	/**
	 * recovery getter
	 * @return bool $value
	 */
	public function getRecovery()
	{
		return $this->getState('__recovery');
	}
	
}
