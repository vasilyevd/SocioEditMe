<?php

class ViewAction extends CAction
{
    public function run()
    {
        if (!isset($_GET['id'])) {
            throw new CHttpException(400, 'Некорректный запрос.');
        }
        $controller = $this->getController();

        $controller->render('view', array(
            'model' => $controller->loadModel($_GET['id']),
        ));
    }
}
