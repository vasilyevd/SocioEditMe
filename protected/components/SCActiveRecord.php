<?php
/**
 * SCActiveRecord class file.
 *
 * @author LSD <vasilyev.dmitro@gmail.com>
 * @license http://www.yiiframework.com/license/
 */

/**
 * SCActiveRecord is the base class for classes representing relational data.
 *
 * It implements the active record design pattern, a popular Object-Relational Mapping (ORM) technique.
 * Please check {@link http://www.yiiframework.com/doc/guide/database.ar the Guide} for more details
 * about this class.
 *
 * @property CDbCriteria $dbCriteria The query criteria that is associated with this model.
 * This criteria is mainly used by {@link scopes named scope} feature to accumulate
 * different criteria specifications.
 * @property CActiveRecordMetaData $metaData The meta for this AR class.
 * @property CDbConnection $dbConnection The database connection used by active record.
 * @property CDbTableSchema $tableSchema The metadata of the table that this AR belongs to.
 * @property CDbCommandBuilder $commandBuilder The command builder used by this AR.
 * @property array $attributes Attribute values indexed by attribute names.
 * @property boolean $isNewRecord Whether the record is new and should be inserted when calling {@link save}.
 * This property is automatically set in constructor and {@link populateRecord}.
 * Defaults to false, but it will be set to true if the instance is created using
 * the new operator.
 * @property mixed $primaryKey The primary key value. An array (column name=>column value) is returned if the primary key is composite.
 * If primary key is not defined, null will be returned.
 * @property mixed $oldPrimaryKey The old primary key value. An array (column name=>column value) is returned if the primary key is composite.
 * If primary key is not defined, null will be returned.
 * @property string $tableAlias The default table alias.
 *
 */


abstract class SCActiveRecord extends CActiveRecord
{


	/**
	 * Finds the number of rows satisfying the specified query condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $condition query condition or criteria.
	 * @param array $params parameters to be bound to an SQL statement.
	 * @return string the number of rows satisfying the specified query condition. Note: type is string to keep max. precision.
	 * lsd
	 */
	public function count($condition='',$params=array())
	{
		Yii::trace(get_class($this).'.count() lsd','SCActiveRecord');
		$builder=$this->getCommandBuilder();
		$criteria=$builder->createCriteria($condition,$params);
		$this->applyScopes($criteria);

		if(empty($criteria->with))
			return $builder->createCountCommand($this->getTableSchema(),$criteria)->queryScalar();
		else
		{
			Yii::trace(get_class($this).'with not empty lsd','SCActiveRecord');
			$finder=new SCActiveFinder($this,$criteria->with);
			return $finder->count($criteria);
		}
	}

}