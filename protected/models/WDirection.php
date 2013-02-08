<?php

/**
 * This is the model class for table "org_direction".
 *
 * The followings are the available columns in table 'org_direction':
 * @property integer $id
 * @property string $name
 */
class WDirection extends Direction
{
    /**
     * @return array relational rules.
     */
    public function relations()
    {
	    return CMap::mergeArray(parent::relations(),
		    array(
			    'catorganizations' => array(self::MANY_MANY, 'WCatorganization', Yii::app()->params['db2name'].'org_catorganization_direction(direction_id, catorganization_id)',),
		    )
	    );
    }
}
