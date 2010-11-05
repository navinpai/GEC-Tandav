<?php
/**
 * @version		$Id: default.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="clr"></div>

<?php if($modLogo): ?>
<div id="k2QuickIconsTitle">
	<a href="index.php?option=com_k2" title="<?php echo JText::_('K2 dashboard'); ?>">
		<span>K2</span>
	</a>
</div>
<?php endif; ?>

<div id="k2QuickIcons"<?php if(!$modLogo): ?> class="k2NoLogo"<?php endif; ?>>
	<?php if(file_exists($quickIconsFile)) @ include($quickIconsFile); ?>
</div>

<div class="clr"></div>
