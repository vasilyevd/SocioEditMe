<?php /** @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/socio.css" rel="stylesheet">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>


	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico">
</head>
<body>
	<div id="main-header" class="container">
		<div id="main-menu" style="height: 25px; margin: 10px 0;">
			<!-- MAIN MENU -->
			<?php
			$menu = $this->beginWidget('bootstrap.widgets.TbMenu', array(
					'type'=>'pills',
					//'htmlOptions'=>array('class'=>'nav-pills'),
					'items'=>array(
						array('label'=>'Организации',
							'url'=>array('/catorganization/index'),
							'active'=>$this->sectionMain == 'org',
							'items'=>array(
								array('label'=>'Общественные',
									'url'=>array('/catorganization/index'),
									'active'=>$this->sectionMain == 'org',
								),
								array('label'=>'Государственные',
									'url'=>'',
									'active'=>$this->sectionMain == 'gov',
								),
								array('label'=>'Доноры',
									'url'=>'',
									'active'=>$this->sectionMain == 'donors',
								),
							)
						),
						'',
						array('label'=>'Информация',
							'url'=>'',
							'active'=>$this->sectionMain == 'inf',
							'items'=>array(
								array('label'=>'Законы',
									'url'=>array('/document/index'),
									'active'=>$this->sectionMain == 'doc',
								),
								array('label'=>'О доступности',
									'url'=>'',
									'active'=>$this->sectionMain == 'inf',
								),
								array('label'=>'Практика',
									'url'=>'',
									'active'=>$this->sectionMain == 'metodics',
								),
							)
						),
						'',
						array('label'=>'Открытость',
							'url'=>'',
							'active'=>$this->sectionMain == 'otkr',
						),
					),
				));

			foreach ($menu->items as $key=>$item) {
				if ($item['active'] && !empty($item['items'])) {
					$this->_subMenu = $item['items'];
				}
				unset($menu->items[$key]['items']);
			}

			$this->endWidget();
			?>
		</div>
	</div>

	<div id="section-header" style="background: none repeat scroll 0 0 #CECECE; height: 67px; margin-bottom: 10px;">
		<div class="container" style="position: relative;">
			<div class="mainlogo" style="float: left;">
				<?=CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/logo.png', 'logo', array('style'=>'padding: 10px;')), '/', array('style'=>'outline:none;')); ?>
			</div>

			<div id="collapse_submenu" class="" style="bottom: 0; left: 200px; padding-left: 20px; position: absolute;">
			<?php
			if (isset($this->_subMenu)) {
				$menu_sub = $this->beginWidget('zii.widgets.TbMenu', array(
						'type'=>'pills',
						'items' => $this->_subMenu,
					));

				foreach ($menu_sub->items as $key=>$item) {
					if ($item['active'] && !empty($item['items'])) {
						$this->_subsubMenu = $item['items'];
					}
					unset($menu_sub->items[$key]['items']);
				}

				$this->endWidget();
			}
			?>
			</div>

		</div>
	</div>


	<div class="content container <?php echo $this->contentClass; ?>">
		<div id="subsubmenu">
			<?php
			if (isset($this->_subsubMenu)) {
				$this->widget('zii.widgets.TbMenu', array(
						'type'=>'tabs',
						'items' => $this->_subsubMenu,
					));
			}
			?>
		</div>
		<?php echo $content; ?>
	</div>

	<div id="footer" class="footer text-mini">
		<div class="container"><div class="row">

			<div class="span3 copyright">
				<?php echo CHtml::image($this->createUrl('images/logow.png'), 'Logo', array('class'=>'logo')); ?>
				<p>Copyright &copy; <?php echo date('Y'), ". All Right Reserved" ?></p>
				<p><em>Условия</em> и <em>Политика конфиденциальности</em> в соответствии с которыми наши услуги предоставляются для Вас</p>
			</div>

			<div class="span6">
				<div class="text-block">
				<p class="topic-pre">Что такое SOCIO?</p>
				<p class="topic">Социальная платформа для общения, самовыражения и самореализации</p>
				<p class="em">Вырази себя в широком спектре общественной жизни - люди, семьи, публичные личности, эксперты, сообщества, фото, видео, музыка, газеты, публикации, оффициальные страницы организаций и общественных объединений, государственная и общественная инфраструктура и многое другое...</p>
				</div>
				<div class="text-block">
					<p class="topic-pre">Раскажи о нас</p>
					<p>Иконки других социальных сетей </p>
				</div>
				<div class="link-menu tar">
				<?=CHtml::link('Карта сайта', '#'); ?>
				<?=CHtml::link('Отзывы и предложения', '#'); ?>
				<?=CHtml::link('Связаться с нами', '#'); ?>
				</div>
			</div>

			<div class="span3 tac">
				<?=CHtml::image($this->createUrl('images/other/coalition128.png'), 'Logo', array('style'=>'width: 110px;')); ?>
				<?=CHtml::image($this->createUrl('images/other/aemb128.gif'), 'Logo', array('style'=>'width: 110px;')); ?>
				<?=CHtml::image($this->createUrl('images/other/irf.jpg'), 'Logo'); ?>
				<?=CHtml::image($this->createUrl('images/other/prometei.png'), 'Logo'); ?>

			</div>

			<div class="span12 tac">
			</div>
			</div></div>
	</div>
<div>
	T: <?=sprintf('%0.5f',Yii::getLogger()->getExecutionTime())?> с.,
	M: <? echo round(memory_get_peak_usage()/(1024*1024),2)."MB"?>,
	DB: <?php print_r(Yii::app()->db->getStats()); ?>
</div>

</body>
</html>
