<?php

class UpdateAction extends CAction
{
    public function run()
    {
        if (!isset($_GET['id'])) {
            throw new CHttpException(400, 'Некорректный запрос.');
        }
        $controller = $this->getController();
        $modelName = ucfirst($controller->getId());

        // Load current model.
        $model = $controller->loadModel($_GET['id']);

        // Uncomment the following line if AJAX validation is needed
        $controller->performAjaxValidation($model);

        if (isset($_POST[$modelName])) {
            $model->attributes = $_POST[$modelName];

            if ($model->save()) {
                $controller->redirect(array('view', 'id' => $model->id));
            }
        }

        $controller->render('update', array(
            'model' => $model,
        ));
    }
}
