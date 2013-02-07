<?php
$this->breadcrumbs=array(
    'Catorganizations'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('label'=>'List Catorganization','url'=>array('index')),
    array('label'=>'Create Catorganization','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('catorganization-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Manage Catorganizations</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
    'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
    'id'=>'catorganization-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'id',
        'name',
        'registration_date',
        'address',
        'address_id',
        'city_id',
        /*
        'region_id',
        'chief_fio',
        'registration_num',
        'phone',
        'website',
        'email',
        'organization_id',
        'is_legal',
        'action_area',
        'directions_more',
        'logo',
        'is_branch',
        'branch_master',
        'is_verified',
        'word_name',
        'word_registration_date',
        'word_address',
        'word_city',
        'word_region',
        'word_contact',
        'word_contact_position',
        'word_is_legal',
        'word_is_branch',
        'word_branch_master',
        'word_registration_num',
        */
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>
