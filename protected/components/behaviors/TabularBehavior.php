<?php

/**
 * 'TabularBehavior' handles new related models validation and creation along
 * side with it's owner model in bulk format.
 */
class TabularBehavior extends CActiveRecordBehavior
{
    public $relations = array();
    private $_settings;

    /**
     * Calls Tabular method for all relations, finds and validates relation
     * models. Models from new relation attributes is not affected by
     * 'EActiveRecordRelationBehavior' because this method is called after it.
     */
    public function beforeValidate()
    {
        // Find relations settings once.
        if (!isset($this->_settings)) {
            $this->_settings = $this->owner->relations();
        }

        foreach ($this->relations as $rel) {
            // Call owners tabular method.
            $method = $rel['name'] . 'Tabular';
            list($valid, $tabular) = $this->owner->$method();

            $this->owner->$rel['name'] = $tabular;

            // Check for validation errors.
            if (!$valid) {
                $this->owner->addError($rel['name'], 'Неверно задано поле ' . $this->owner->getAttributeLabel($rel['name']));
            }
        }
    }

    /**
     * Saves all 'MANY_MANY' and 'HAS_MANY' related models. Their link ID
     * attribute is set to null, because owner is new and don't have ID yet.
     * Relation link creation is handled by 'EActiveRecordRelationBehavior'
     * later in 'afterSave'.
     * Saves all 'BELONGS_TO' master relations and sets owner model link ID.
     */
    public function beforeSave()
    {
        foreach ($this->relations as $rel) {
            if ($this->_settings[$rel['name']][0] === CActiveRecord::MANY_MANY ||
                $this->_settings[$rel['name']][0] === CActiveRecord::HAS_MANY
            ) {
                foreach ($this->owner->$rel['name'] as $m) {
                    $m->save();
                }
            } elseif ($this->_settings[$rel['name']][0] === CActiveRecord::BELONGS_TO) {
                if ($this->owner->$rel['name'] === null) {
                    // Set link ID attribute of owner model as null.
                    $this->owner->setAttribute($this->_settings[$rel['name']][2], null);
                } else {
                    // Save related model.
                    $this->owner->{$rel['name']}->save();
                    // Set link ID attribute of owner model as new relation ID.
                    $this->owner->setAttribute($this->_settings[$rel['name']][2], $this->owner->{$rel['name']}->id);
                }
            }
        }
    }

    /**
     * Deletes homeless models for 'HAS_MANY' relations, based on 'delete'
     * option. This method is called after 'EActiveRecordRelationBehavior' so
     * all valid relations are set and old relations marked with link ID null.
     */
    public function afterSave()
    {
        foreach ($this->relations as $rel) {
            if ($this->_settings[$rel['name']][0] === CActiveRecord::HAS_MANY) {
                // If set delete option.
                if (array_key_exists('delete', $rel) && $rel['delete'] === true) {
                    // Find all related models with null ID link.
                    $deleteModels = call_user_func($this->_settings[$rel['name']][1] . '::model')->findAllByAttributes(array($this->_settings[$rel['name']][2] => null));
                    foreach ($deleteModels as $m) {
                        $m->delete();
                    }
                }
            }
        }
    }
}
