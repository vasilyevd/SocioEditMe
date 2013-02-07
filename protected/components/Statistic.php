<?php
class Statistic
{

	public static function dostup_all($n=true)
	{
		$criteria = new CDbCriteria;
		$criteria->with=array('anketa'=>array('select'=>'anketa.total as total'));
		$criteria->select = array('COUNT(DISTINCT `t`.`id`) as cnt');
		$criteria->group= 'anketa.total';
		
		
		 $criteria->addColumnCondition(array("active"=>Object::ACTIVE_YES));

		// $result = Object::model()->count($criteria);
		$result['all'] = 0;
		foreach (Object::model()->count($criteria) as $v) {
			if ($n || (!$n && !$v['total']==0)) { 
			$result[$v['total']]= $v['cnt'];
			$result['all'] += $v['cnt'];
			}
		}
		return $result;
	}
	
	public static function dostup_criterial($fo)
	{
		$criteria = new CDbCriteria;
		$criteria->with=array('anketa'=>array('select'=>' anketa.total as total'));
		$criteria->select = array('COUNT(DISTINCT `t`.`id`) as cnt');
		$criteria->group= 'anketa.total';
		
		// Petition
		if ((int)$fo['Petition']['status']==1) {
			$criteria->with['petitions']=array();
			$add = ($fo['Petition']['status2']==1) ? ' AND petitions.status=1' : '';
			$criteria->addCondition('petitions.object_id=t.id  AND petitions.show=1'.$add, false);
		}
		
		// cat
		if (!(int)$fo['cat']['type']>0) {
				if ((int)$fo['cat']['root']>0) {
					$criteria->with['ncategory']=array();
					$criteria->compare('ncategory.root', (int)$fo['cat']['root'], false);
				}
				if ((int)$fo['cat']['lvl2']>0) {
					$criteria->with['ncategory']=array();
					$cat_lvl2 = Category::model()->findByPk($fo['cat']['lvl2']);						
					$criteria->addCondition('ncategory.lft > '.$cat_lvl2->lft.' AND ncategory.rgt < '.$cat_lvl2->rgt);
				}
		} else {
				$criteria->with['ncategory']=array();
				$criteria->compare('ncategory.id', (int)$fo['cat']['type'], false);
		}
		
		// Address
		if (!(int)$fo['Address']['city_id']>0) {
				if (isset($fo['c']['region']) && $fo['c']['region']>0) {
					$criteria->with['city']=array('select'=>false);
					$criteria->compare('city.region_id', $fo['c']['region'], false);
				}
				if (isset($fo['c']['district']) && $fo['c']['district']>0) {
					$criteria->with['city']=array('select'=>false);
					$criteria->compare('city.district',$fo['c']['district'],false);
				}
		} else {
				// $criteria->with['citys']=array('select'=>false);
				$criteria->compare('t.city',$fo['Address']['city_id'],false);
		}
		
		if ($fo['obj']['text'])
		  $criteria->compare('t.name', $fo['obj']['text'], true);

		
		if ((int)$fo['answer']>0) {
			$criteria->with['anketa']= array_merge ($criteria->with['anketa'], array(
				'with'=>array(
					'answers'=>array(
						'condition'=>'answers.question_id=:question_id AND answers.aswer=:aswer',
						'params'=>array(':question_id'=>11, ':aswer'=>(int)$fo['answer'])
					)
				)
			));
		}

		$criteria->addColumnCondition(array("t.active"=>Object::ACTIVE_YES));
		
		
		// $criteria->addColumnCondition(array("active"=>Object::ACTIVE_YES));
 		
$criteria->together = true;
		
		// $result = Object::model()->count($criteria);
		$result['all'] = 0;
		$result[0]=0;
		foreach (Object::model()->cache(3600, null, 2)->count($criteria) as $v) {
			  if (isset ($v['total'])) { 
			$result[(int)$v['total']]+= $v['cnt'];
			$result['all'] += $result[$v['total']];
			 } else {
				$result['not'] += $v['cnt'];
			 }
			 $result['allall'] += $v['cnt'];

		}
		return $result;
	}


	public static function dostup_in_area($fo)
	{
		$cache_key=false;
		
		$criteria = new CDbCriteria;
		$criteria->with=array('anketa'=>array('select'=>'anketa.total as total'));
		$criteria->select = array('COUNT(DISTINCT `t`.`id`) as cnt');
		$criteria->group= 'anketa.total';
		
		// Address
		if (!(int)$fo['Address']['city_id']>0) {
				if (isset($fo['c']['region']) && $fo['c']['region']>0) {
					$criteria->with['city']=array('select'=>false);
					$criteria->compare('city.region_id', $fo['c']['region'], false);
					$cache_key = 'Region_'.$fo['c']['region'];
				}
				if (isset($fo['c']['district']) && $fo['c']['district']>0) {
					$criteria->with['city']=array('select'=>false);
					$criteria->compare('city.district',$fo['c']['district'],false);
					$cache_key = 'District_'.$fo['c']['district'];
				}
		} else {
				// $criteria->with['citys']=array('select'=>false);
				$criteria->compare('t.city',$fo['Address']['city_id'],false);
				$cache_key = 'City_'.$fo['Address']['city_id'];
		}

		$criteria->addColumnCondition(array("active"=>Object::ACTIVE_YES));

		$criteria->together = true;
		
		// $result = Object::model()->count($criteria);
		$result['all'] = 0;
		$result[0]=0;
		foreach (Object::model()->cache(360, null, 1)->count($criteria) as $v) {
			 if (isset ($v['total'])) { 
				$result[(int)$v['total']]+= $v['cnt'];
				$result['all'] += $result[$v['total']];
			 } else {
				$result['not'] += $v['cnt'];
			 }
			 $result['allall'] += $v['cnt'];
		}
		
		//������������� ��� ���������� �������� �� ����������
		if ($cache_key!==false) {
			//echo 'delete кеш';
			Yii::app()->cache->delete('CountObjectsIn'.$cache_key);
			Yii::app()->cache->set('CountObjectsIn'.$cache_key, (int)$result['allall'], 3600);
		}

		return $result;
	}
	
}

?>