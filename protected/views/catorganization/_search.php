<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'registration_date',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'address',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'address_id',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'city_id',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'region_id',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'chief_fio',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'registration_num',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'phone',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'website',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'organization_id',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'is_legal',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'action_area',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'directions_more',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'logo',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'is_branch',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'branch_master',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'is_verified',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'word_name',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'word_registration_date',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'word_address',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'word_city',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'word_region',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'word_contact',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'word_contact_position',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'word_is_legal',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'word_is_branch',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'word_branch_master',array('class'=>'span5','maxlength'=>128)); ?>

    <?php echo $form->textFieldRow($model,'word_registration_num',array('class'=>'span5','maxlength'=>128)); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type'=>'primary',
            'label'=>'Search',
        )); ?>
    </div>

<?php $this->endWidget(); ?>
