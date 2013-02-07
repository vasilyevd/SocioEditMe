<?php
//$this->h1='Войти на сайт';
$this->breadcrumbs=array(
    'Войти на сайт',
);
?>

<div class="span7 offset1">
 
<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
						'mail'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
				)
    ); ?>

	
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'login-form',
    'type'=>'horizontal',
		'htmlOptions'=>array('class'=>'well'),
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>false,
		'focus'=>array($model, 'email'),
		'clientOptions'=>array(
			'validateOnChange' => true,
			'validateOnSubmit'=>true,
		),
)); ?>

<?php echo $form->errorSummary($model); ?>	

	<fieldset>
	
	<?php echo $form->textFieldRow($model, 'email', array('id'=>'PersonUser_email', 'class'=>'span4','maxlength'=>255)); ?>
	<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span4', 'maxlength'=>255)); ?>
	 <?php echo $form->checkBoxRow($model, 'rememberMe', array('id'=>'rememberMepage')); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Войти')); ?>
</fieldset>
	
	<?php $this->endWidget(); ?>	
</div>
<noindex>
<div class="span3">
<a href="/person/auth/register" rel="nofollow" class="btn btn-block btn-success">Зарегистрироваться</a>
<a href="/Person/auth/passRequest/" rel="nofollow" class="btn btn-block">Востановление пароля</a>
</div>
</noindex>