<?php

/**
 * This is the model class for table "org_direction".
 *
 * The followings are the available columns in table 'org_direction':
 * @property integer $id
 * @property string $name
 */
class Direction extends BaseDirection
{

	private $_dbname;

	public function getDbName(){
		$name = preg_match("/dbname=([^;]*)/", $this->dbConnection->connectionString, $matches);
		$this->_dbname = $matches[1];
		return $this->_dbname;
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName(){
		$name = preg_match("/dbname=([^;]*)/", $this->dbConnection->connectionString, $matches);
		$this->_dbname = $matches[1];
		return $this->_dbname.'.org_direction';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array_merge(parent::relations(),
		array(
			'catorganizations' => array(self::MANY_MANY, 'Catorganization', Yii::app()->params['db2name'].'.org_catorganization_direction(direction_id, catorganization_id)',),
		));
	}

}

