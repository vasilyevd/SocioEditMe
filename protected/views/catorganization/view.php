<?php
$this->breadcrumbs=array(
    'Catorganizations'=>array('index'),
    $model->name,
);
?>

<?php echo CHtml::link('Список Организаций', array('index'), array('class' => 'btn')); ?>

<?php echo CHtml::link('Изменить Организацию', array('update', 'id' => $model->id), array('class' => 'btn')); ?>

<?php echo CHtml::link('Удалить Организацию', '#', array(
    'submit' => array('delete', 'id' => $model->id),
    'confirm' => 'Вы уверены, что хотите удалить данный элемент?',
    'class' => 'btn btn-warning',
)); ?>

<h1><?php echo $model->name; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'registration_date',
        'address',
        'address_id',
        'city_id',
        'region_id',
        'chief_fio',
        'registration_num',
        'phone',
        'website',
        'email',
        array(
            'name' => 'action_area',
            'value' => Lookup::item('OrganizationActionArea', $model->action_area),
        ),
        array(
            'name' => 'directions',
            'value' => CHtml::encode(implode(', ', CHtml::listData($model->directions, 'name', 'name'))),
        ),
        'directions_more',
        array(
            'name' => 'organization',
            'type' => 'raw',
            'value' => empty($model->organization) ? '' : CHtml::link(CHtml::encode($model->organization->name), array('organization/view', 'id' => $model->organization->id), array('target' => '_blank')),
        ),
        array(
            'name' => 'logo',
            'type' => 'raw',
            'value' => CHtml::link(CHtml::encode($model->logo), $model->getUploadUrl('logo'), array('target' => '_blank')),
        ),
        array(
            'name' => 'is_legal',
            'value' => $model->is_legal ? 'Да' : 'Нет',
        ),
        array(
            'name' => 'is_branch',
            'value' => $model->is_legal ? 'Да' : 'Нет',
        ),
        'branch_master',
        array(
            'name' => 'is_verified',
            'value' => $model->is_legal ? 'Да' : 'Нет',
        ),
    ),
)); ?>
