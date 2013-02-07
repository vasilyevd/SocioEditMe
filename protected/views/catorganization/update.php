<?php
$this->breadcrumbs=array(
    'Catorganizations'=>array('index'),
    $model->name=>array('view','id'=>$model->id),
    'Update',
);
?>

<?php echo CHtml::link('Список Организаций', array('index'), array('class' => 'btn')); ?>

<?php echo CHtml::link('Профиль Организации', array('view', 'id' => $model->id), array('class' => 'btn')); ?>

<h1>Изменить Общественную Организацию</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
