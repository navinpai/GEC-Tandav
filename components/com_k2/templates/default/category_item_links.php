<?php
/**
 * @version		$Id: category_item_links.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Define default image size (do not change)
$image = 'image'.$this->item->params->get($this->item->itemGroup.'ImgSize');

?>

<!-- Start K2 Item Layout (links) -->
<div class="catItemView group<?php echo ucfirst($this->item->itemGroup); ?><?php if($this->item->params->get('pageclass_sfx')) echo ' '.$this->item->params->get('pageclass_sfx'); ?>">
	  <?php if($this->item->params->get('catItemTitle')): ?>
	  <!-- Item title -->
	  <h3 class="catItemTitle">
	  	<?php if ($this->item->params->get('catItemTitleLinked')): ?>
			<a href="<?php echo $this->item->link; ?>">
	  		<?php echo $this->item->title; ?>
	  	</a>
	  	<?php else: ?>
	  	<?php echo $this->item->title; ?>
	  	<?php endif; ?>
	  </h3>
	  <?php endif; ?>
</div>
<!-- End K2 Item Layout (links) -->
