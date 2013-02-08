<?php

/**
 * This is the model class for table "org_catorganization".
 *
 * The followings are the available columns in table 'org_catorganization':
 * @property integer $id
 * @property string $name
 * @property string $registration_date
 * @property string $address
 * @property integer $address_id
 * @property integer $city_id
 * @property integer $region_id
 * @property string $chief_fio
 * @property string $registration_num
 * @property string $phone
 * @property string $website
 * @property string $email
 * @property integer $organization_id
 * @property integer $is_legal
 * @property integer $action_area
 * @property string $directions_more
 * @property string $logo
 * @property integer $is_branch
 * @property string $branch_master
 * @property integer $is_verified
 *
 * The followings are the available model relations:
 * @property CatorganizationDirection[] $catorganizationDirections
 */
class Catorganization extends BaseCatorganization
{

	private $_dbname;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Catorganization the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// отдаём соединение, описанное в компоненте db2
	public function getDbConnection(){
		return Yii::app()->db2;
	}

	public function getDbName(){
		$name = preg_match("/dbname=([^;]*)/", $this->dbConnection->connectionString, $matches);
		$this->_dbname = $matches[1];
		return $this->_dbname;
	}

	/**
	 * @return string the associated database table name
	 * возвращаем имя таблицы вместе с именем БД
	 */
	public function tableName(){
		return $this->getDbName().'.org_catorganization';
	}

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'organization' => array(self::BELONGS_TO, 'Organization', 'organization_id'),
            'directions' => array(self::MANY_MANY, 'Direction', $this->getDbName().'.org_catorganization_direction(catorganization_id, direction_id)'),
        );
    }

}
