<?php

class EditableUpdateAction extends BaseMassmediaAction
{
    public function run()
    {
        $controller = $this->getController();
        $modelName = ucfirst($controller->getId());

        // Or you can add import 'ext.editable.*' to config.
        Yii::import('bootstrap.widgets.TbEditableSaver');

        // Set classname of model to be updated.
        $saver = new TbEditableSaver($modelName);
        $saver->update();
    }
}
