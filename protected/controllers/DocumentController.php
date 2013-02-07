<?php

class DocumentController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view', 'docauthorAutoComplete'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update', 'editableUpdate'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete'),
                'users'=>array('*'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // General CRUD actions.
            'view' => 'application.components.actions.ViewAction',
            'create' => 'application.components.actions.CreateAction',
            'update' => 'application.components.actions.UpdateAction',
            'delete' => 'application.components.actions.DeleteAction',
            // 'index' => 'application.components.actions.IndexAction',
            'admin' => 'application.components.actions.AdminAction',

            // Editable fields and widgets update model action.
            'editableUpdate' => 'application.components.actions.EditableUpdateAction',
        );
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new Document('search');
        $model->unsetAttributes(); // clear any default values
        if(isset($_GET['Document']))
            $model->attributes=$_GET['Document'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Search model for auto complete term.
     * @param string $term the search text.
     */
    public function actionDocauthorAutoComplete($term)
    {
        header('Content-type: application/json');
        $criteria = new CDbCriteria();

        $criteria->compare('name', $term, true);
        $criteria->limit = 5;

        $data = Docauthor::model()->findAll($criteria);

        $result = array();
        foreach ($data as $m) {
            $result[] = array(
                'value' => $m->name,
                'label' => $m->name,
            );
        }

        echo CJSON::encode($result);
        Yii::app()->end();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=Document::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    public function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='document-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
