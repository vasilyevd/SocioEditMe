<?php
$this->breadcrumbs=array(
    'Catorganizations'=>array('index'),
    'Create',
);
?>

<?php echo CHtml::link('Список Организаций', array('index'), array('class' => 'btn')); ?>

<h1>Создать Общественную Организацию</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
