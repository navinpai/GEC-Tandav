<?php
defined( '_JEXEC' ) or die( 'Access to this location is RESTRICTED.' );
echo '<?xml version="1.0" encoding="utf-8"?'.'>'; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<?php require("head_includes.php"); ?>
<link REL="SHORTCUT ICON" HREF="tandav.ico">
<body>
<?php if (($this->params->get('showStyleswitcher')) !=0) : ?>
	<div id="ss">
		<div id="styleswitcher">
			<noscript>Styleswitcher needs Javascript enabled!</noscript>
			<ul>
				<li><a title="Style I" href="index.php" rel="styles1" class="styleswitch"><img src="templates/<?php echo $this->template ?>/images/styles1.gif" alt="style 1"/></a></li>
				<li><a title="Style II" href="index.php" rel="styles2" class="styleswitch"><img src="templates/<?php echo $this->template ?>/images/styles2.gif" alt="style 2"/></a></li>
				<li><a title="Style III" href="index.php" rel="styles3" class="styleswitch"><img src="templates/<?php echo $this->template ?>/images/styles3.gif" alt="style 3"/></a></li>
				<li><a title="Style IV" href="index.php" rel="styles4" class="styleswitch"><img src="templates/<?php echo $this->template ?>/images/styles4.gif" alt="style 4"/></a></li>
			</ul>				
		</div>
	</div>
<?php endif;?>
<div id="wrapper">
	<div id="bg_up">
		<div id="header_container">
			<div id="navigation_container">
				<?php if($this->countModules('user3')) : ?>
					<div id="navigation">
			             <jdoc:include type="modules" name="user3" style="xhtml" />
					</div>
				<?php endif; ?>
			</div>
			<div id="header">
				<div id="toggout">
					<?php if (($this->params->get('showStyleswitcher')) !=0) : ?>
						<div class="toggle"> <a title="Toggle styleswitcher" href="#" id="toggle_ss"></a></div>
					<?php endif;?>
				</div>
				<div id="headermodule">
		             <jdoc:include type="modules" name="headermodule"/>
				</div>
				<div id="logo">
					<?php if (($this->params->get('showSitename')) !=0) : ?>				
						<h1><?php echo $mainframe->getCfg('sitename');?></h1>
					<?php endif;?>
				</div>	
				<div id="searchbox">
					<div id="search">
						<div id="search_inner">
				             <jdoc:include type="modules" name="user4"/>
						</div>
					</div>
				</div>	
			</div>
		</div>
		<div id="container">
			<div id="page_content">
				<?php if($this->countModules('top1 or top2')) : ?>
					<div id="user_top">
						<?php if($this->countModules('top1')) : ?>
							<div id="top1">
					           <jdoc:include type="modules" name="user1" style="rounded" />
							</div>
						<?php endif; ?>			
						<?php if($this->countModules('top2')) : ?>
							<div id="top2">
					           <jdoc:include type="modules" name="user2" style="rounded" />
							</div>
						<?php endif; ?>			
					</div>					
				<?php endif; ?>		
			
				<?php if($this->countModules('left')) : ?>
					<div id="sidebar_left">
			             <jdoc:include type="modules" name="left" style="rounded" />
					</div>
				<?php endif; ?>		
				<?php if($this->countModules('right')) : ?>
					<div id="sidebar_right">
			             <jdoc:include type="modules" name="right" style="rounded" />
					</div>
				<?php endif; ?>		
			    <div id="content_out<?php echo $contentwidth; ?>">
					<div id="content">
						<jdoc:include type="message" />						
						<jdoc:include type="component" />
					</div>
				</div>
			
			</div>	
		</div>
			<div id="container2">
				<?php if($this->countModules('user1 or user2')) : ?>
					<div id="user_modules1">
						<?php if($this->countModules('user1')) : ?>
							<div id="user1">
					           <jdoc:include type="modules" name="user1" style="rounded" />
							</div>
						<?php endif; ?>			
						<?php if($this->countModules('user2')) : ?>
							<div id="user2">
					           <jdoc:include type="modules" name="user2" style="rounded" />
							</div>
						<?php endif; ?>			
					</div>					
				<?php endif; ?>		
				<div id="footer">
					<jdoc:include type="modules" name="footer" />
				</div>
				<div id="box"></div>
				<div id="scroll_up"> <a href="#" id="gotop" title="Scroll to Top"></a></div>
				<div id="designed_by">
			  		<p>&copy; <a title="<?php echo $mainframe->getCfg('sitename');?>" href="index.php"><?php echo $mainframe->getCfg('sitename');?></a> | designed by: <a title="The Robot Ain't Dead">R[o]b[o] Zombie Productions</a></p>
			  </div>
				<div class="clr"></div>
			</div>
	</div>
	<div id="bottom"></div>
</div>
<jdoc:include type="modules" name="debug" style="xhtml" />
</body>
</html>