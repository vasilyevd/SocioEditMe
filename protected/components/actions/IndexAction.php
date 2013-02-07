<?php

class IndexAction extends CAction
{
    public function run()
    {
        $controller = $this->getController();
        $modelName = ucfirst($controller->getId());

        $dataProvider = new CActiveDataProvider($modelName);
        $controller->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
}
