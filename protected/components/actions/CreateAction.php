<?php

class CreateAction extends CAction
{
    public function run()
    {
        $controller = $this->getController();
        $modelName = ucfirst($controller->getId());

        // Create the model.
        $model = new $modelName;

        // Uncomment the following line if AJAX validation is needed.
        $controller->performAjaxValidation($model);

        if (isset($_POST[$modelName])) {
            $model->attributes = $_POST[$modelName];

            if ($model->save()) {
                $controller->redirect(array('view', 'id' => $model->id));
            }
        }

        $controller->render('create', array(
            'model' => $model,
        ));
    }
}
