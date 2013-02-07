<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('registration_date')); ?>:</b>
    <?php echo CHtml::encode($data->registration_date); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
    <?php echo CHtml::encode($data->address); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('address_id')); ?>:</b>
    <?php echo CHtml::encode($data->address_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('city_id')); ?>:</b>
    <?php echo CHtml::encode($data->city_id); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('region_id')); ?>:</b>
    <?php echo CHtml::encode($data->region_id); ?>
    <br />

</div>
