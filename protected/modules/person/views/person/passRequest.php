<?php
$this->h1='Востановление пароля';
?>
<div id="">
	<?php if(Yii::app()->user->hasFlash('mail')):?>
    <h2>
        <?php echo Yii::app()->user->getFlash('mail'); ?>
    </h2>
	<?php endif; ?>
	<div class="form center">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'user-groups-passrequest-form',
			'enableAjaxValidation'=>true,
			'clientOptions'=>array(
				'validateOnChange' => true,
				'validateOnSubmit'=>true,
			),

		)); ?>
		<?php echo $form->error($model,'all'); ?>
		
		<div class="h2">Запрос на востановление пароля</div>
		
<div class="row_wrap clear_fix">
	<div class="settings_label fl_l ta_r">
			<?php echo $form->label($model,'username'); ?>
	</div>
	<div class="settings_labeled fl_l">
			<?php echo $form->textField($model,'username'); ?>
			<?php echo $form->error($model,'username'); ?>
	</div>
</div>

<div class="row_wrap clear_fix">
	<div class="settings_label fl_l ta_r">
	</div>
	<div class="settings_labeled fl_l">
		<strong>ИЛИ</strong>
	</div>
</div>
		
		
<div class="row_wrap clear_fix">
	<div class="settings_label fl_l ta_r">
			<?php echo $form->label($model,'email'); ?>
	</div>
	<div class="settings_labeled fl_l">
			<?php echo $form->textField($model,'email'); ?>
			<?php echo $form->error($model,'email'); ?>
	</div>
</div>
		
		<?php if (isset($model->errors['answer']) && !isset($model->errors['email']) && !isset($model->errors['username'])): ?>
		<div class="row">
			<h2><?php echo $model->errors['question'][0]; ?></h2>
			<?php echo $form->labelEx($model,'answer'); ?>
			<?php echo $form->textField($model,'answer'); ?>
			<?php echo $form->error($model,'answer'); ?>
		</div>
		<?php endif; ?>
		
<div class="row_wrap clear_fix">
	<div class="settings_label fl_l ta_r">
	</div>
	<div class="settings_labeled fl_l">
		<?php // echo CHtml::submitButton(Yii::t('PersonModule.general','Request New Password')); ?>
		<a onclick ="document.getElementById('user-groups-passrequest-form').submit();" class="button"><?php echo Yii::t('PersonModule.general','Request New Password') ?></a>
	</div>
</div>

		<div class="h2">Подтверждение востановления</div>
		<p style="padding: 5px;">Если Вы ранее уже получили код подтверждения на электронную почту то ввести его можно перейдя по ссылке - <?php echo CHtml::link('подтверждение', array('/person/person/recovery'))?></p>
		
		
		<?php $this->endWidget(); ?>
	</div>
</div>