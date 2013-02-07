<?php $this->beginContent('//layouts/main'); ?>

<div class="row">
    <div class="span3">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'list', // '', 'tabs', 'pills' (or 'list')
            // 'stacked' => true, // stacked state for tabs and pills
            'htmlOptions' => array('class' => 'well'), // bg for list
            'items' => $this->menu,
        )); ?>
    </div>

    <div class="span9">
        <?php echo $content; ?>
    </div>
</div>

<?php $this->endContent(); ?>
