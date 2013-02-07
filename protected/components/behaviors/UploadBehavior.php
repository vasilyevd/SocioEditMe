<?php

class UploadBehavior extends CActiveRecordBehavior
{
    public $attributes = array();
    private $_originalModel;

    /**
     * Allow upload attributes to only store not empty 'CUploadedFile' or
     * original filenames and not some random strings.
     */
    public function beforeValidate()
    {
        // Remember original model.
        $originalClass = get_class($this->owner);
        if ($this->owner->isNewRecord) {
            $this->_originalModel = new $originalClass;
        } else {
            $this->_originalModel = call_user_func($originalClass . '::model')->findByPk($this->owner->id);
            // Fix manual 'isNewRecord = false'.
            if ($this->_originalModel === null) {
                $this->_originalModel = new $originalClass;
            }
        }

        foreach ($this->attributes as $attr) {
            // Override attribute only with 'CUploadedFile' instance.
            if (empty($this->owner->$attr) || !($this->owner->$attr instanceof CUploadedFile)) {
                $this->owner->$attr = $this->_originalModel->$attr;
            }
        }
    }

    /**
     * Saves files with model attributes and deletes old ones.
     */
    public function beforeSave()
    {
        foreach ($this->attributes as $attr) {
            if ($this->owner->$attr instanceof CUploadedFile) {
                // Save new upload and set new filename to attribute.
                $upload = $this->owner->$attr;
                $this->owner->$attr = uniqid() . '.' . $upload->getExtensionName();
                $upload->saveAs($this->getUploadPath($attr));

                // Delete old upload on update.
                if (!$this->owner->isNewRecord) {
                    $this->_originalModel->deleteUpload($attr);
                }
            }
        }
    }

    /**
     * Remove all uploads from all attributes.
     */
    public function afterDelete()
    {
        foreach ($this->attributes as $attr) {
            $this->deleteUpload($attr);
        }
    }

    /**
     * Creates full path for upload file.
     * @param string $attribute the name of upload model attribute.
     * @return string unix path.
     */
    public function getUploadPath($attribute)
    {
        return dirname(Yii::app()->basePath)
            . '/uploads/'
            . strtolower(get_class($this->owner)) . '/'
            . $attribute . '/'
            . $this->owner->$attribute;
    }

    /**
     * Creates relative URL for upload file.
     * @param string $attribute the name of upload model attribute.
     * @return string full URL.
     */
    public function getUploadUrl($attribute)
    {
        return Yii::app()->createUrl('uploads/'
            . strtolower(get_class($this->owner)) . '/'
            . $attribute . '/'
            . $this->owner->$attribute
        );
    }

    /**
     * Finds and deletes uploaded file by its attribute.
     * @param string $attribute the name of upload model attribute.
     */
    public function deleteUpload($attribute)
    {
        // Don't delete with empty attribute field.
        // Don't delete placeholder image.
        if (!empty($this->owner->$attribute) && $this->owner->$attribute != 'placeholder.jpg') {
            // Remove all files containing this name.
            $info = pathinfo($this->getUploadPath($attribute));
            $files = glob($info['dirname'] . '/' . $info['filename'] . '*');
            if (!empty($files)) {
                foreach ($files as $f) {
                    unlink($f);
                }
            }

            // Empty attribute.
            $this->owner->$attribute = '';
        }
    }
}
