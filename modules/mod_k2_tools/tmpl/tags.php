<?php
/**
 * @version		$Id: tags.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2TagCloudBlock <?php echo $params->get('moduleclass_sfx'); ?>">
	<?php foreach ($tags as $tag): ?>
	<?php if(!empty($tag->tag)): ?>
	<a href="<?php echo $tag->link; ?>" style="font-size:<?php echo $tag->size; ?>%" title="<?php echo $tag->count.' '.JText::_('items tagged with').' '.$tag->tag; ?>">
		<?php echo $tag->tag; ?>
	</a>
	<?php endif; ?>
	<?php endforeach; ?>
	<div class="clr"></div>
</div>
