<?php
/**
 * @version		$Id: move.php 315 2010-01-14 18:49:25Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<script type="text/javascript">
	//<![CDATA[
	function submitbutton(pressbutton) {
		if(pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		submitform(pressbutton);
	}
	//]]>
</script>

<form action="index.php" method="post" name="adminForm">
	<fieldset style="float:left;">
		<legend><?php echo JText::_('Target Joomla! User Group');?></legend>
		<?php echo $this->lists['group'];?>
	</fieldset>
	<fieldset style="float:left;">
		<legend><?php echo JText::_('Target K2 User Group');?></legend>
		<?php echo $this->lists['k2group'];?>
	</fieldset>
	<fieldset style="clear:both;">
		<legend><?php echo JText::_('Users being moved');?></legend>
		<ol>
			<?php foreach ($this->rows as $row):?>
			<li><?php echo $row->name;?><input type="hidden" name="cid[]" value="<?php echo $row->id;?>" /></li>
			<?php endforeach;?>
		</ol>
	</fieldset>
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
	<?php echo JHTML::_( 'form.token' );?>
</form>
