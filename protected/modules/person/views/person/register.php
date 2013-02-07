<?php
//$this->h1='Регистрация';
$this->breadcrumbs=array(
	'Регистрация',
);
?>

<noindex>
<div class="span8 offset1">

	<?php /** @var BootActiveForm $form */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'=>'register-form',
			'type'=>'horizontal',
			'htmlOptions'=>array('class'=>'well well-small'),
			'enableAjaxValidation'=>false,
			'enableClientValidation'=>true,
			'focus'=>array($model, 'email'),
			'clientOptions'=>array(
				'validateOnChange' => false,
				'validateOnSubmit'=>true,
			),
		)); ?>

	<fieldset>
		<?php echo $form->textFieldRow($model, 'email', array('class'=>'span4', 'maxlength'=>255)); ?>
		<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span4', 'maxlength'=>255)); ?>
		<?php echo $form->passwordFieldRow($model, 'password_confirm', array('class'=>'span4', 'maxlength'=>255)); ?>
		<?php echo $form->textFieldRow($model, 'name', array('class'=>'span4', 'maxlength'=>255)); ?>
		<?php echo $form->textFieldRow($model, 'last_name', array('class'=>'span4', 'maxlength'=>255)); ?>
	</fieldset>

	<?php if(extension_loaded('gd')&& Yii::app()->getModule('person')->enableCaptcha): ?>

	<?php echo $form->captchaRow($model, 'captcha', array('class'=>'span1', 'maxlength'=>25)); ?>

	<?php endif; ?>

	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Регистрация')); ?>


	<?php $this->endWidget(); ?>


</div>

</noindex>
