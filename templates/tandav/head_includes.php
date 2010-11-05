<head>
<jdoc:include type="head" />
<?php
	// inserting mootools
		JHTML::_('behavior.mootools');
?>
<meta name="designer" content="Navin Pai- Robo Zombie Productions" />
<link href="templates/<?php echo $this->template ?>/css/template.css" rel="stylesheet" type="text/css" media="all" />
<link href="templates/<?php echo $this->template ?>/css/<?php echo $this->params->get('colorVariation'); ?>.css" rel="stylesheet" type="text/css" media="all" />
  <!--[if IE 7]>
	  <link href="templates/<?php echo $this->template ?>/css/ie7.css" rel="stylesheet" type="text/css" media="all" />
   <![endif]-->
   <!--[if lt IE 7]>
	  <link href="templates/<?php echo $this->template ?>/css/ie5x6x.css" rel="stylesheet" type="text/css" media="all" />
   <![endif]-->

<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/hover.js"></script>



<?php if (($this->params->get('showStyleswitcher')) !=0) : ?>
	<link rel="stylesheet" type="text/css" href="templates/<?php echo $this->template ?>/css/colors1.css" title="styles1" media="screen" />
	<link rel="stylesheet" type="text/css" href="templates/<?php echo $this->template ?>/css/colors2.css" title="styles2" media="screen" />
	<link rel="stylesheet" type="text/css" href="templates/<?php echo $this->template ?>/css/colors3.css" title="styles3" media="screen" />
	<link rel="stylesheet" type="text/css" href="templates/<?php echo $this->template ?>/css/colors4.css" title="styles4" media="screen" />
	<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/toggle.js"></script>
	<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript">
		var $j = jQuery.noConflict();
	</script>
	<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/jq.css.switch.js"></script>
<?php endif;?>

</head>
<?php
	if($this->countModules("left")&&!$this->countModules("right")){ $contentwidth="left";}
	if($this->countModules("right")&&!$this->countModules("left")){ $contentwidth="right";}
	if($this->countModules("left")&&$this->countModules("right")) {$contentwidth="middle"; }
?>
