<?php
/**
 * @version		$Id: item_comments_form.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<h3><?php echo JText::_('Add comment') ?></h3>

<form action="<?php echo JURI::root();?>index2.php" method="post" id="comment-form" class="form-validate">
	<label class="formComment" for="commentText"><?php echo JText::_( 'Comment' );?></label>
	<textarea rows="20" cols="10" class="inputbox" onblur="if(this.value=='') this.value='<?php echo JText::_( 'enter your comment here...' );?>';" onfocus="if(this.value=='<?php echo JText::_( 'enter your comment here...' );?>') this.value='';" name="commentText" id="commentText"><?php echo JText::_( 'enter your comment here...' );?></textarea>
	
	<label class="formName" for="userName"><?php echo JText::_( 'Name' );?></label>
	<input class="inputbox" type="text" name="userName" id="userName" value="<?php echo JText::_( 'enter your name...' );?>"  onblur="if(this.value=='') this.value='<?php echo JText::_( 'enter your name...' );?>';" onfocus="if(this.value=='<?php echo JText::_( 'enter your name...' );?>') this.value='';" />
	
	<label class="formEmail" for="commentEmail"><?php echo JText::_( 'E-mail' );?></label>
	<input class="inputbox" type="text" name="commentEmail" id="commentEmail" value="<?php echo JText::_( 'enter your e-mail address...' );?>" onblur="if(this.value=='') this.value='<?php echo JText::_( 'enter your e-mail address...' );?>';" onfocus="if(this.value=='<?php echo JText::_( 'enter your e-mail address...' );?>') this.value='';" />
	
	<label class="formUrl" for="commentURL"><?php echo JText::_('URL (optional)');?></label>
	<input class="inputbox" type="text" name="commentURL" id="commentURL" value="<?php echo JText::_( 'enter your site URL...');?>"  onblur="if(this.value=='') this.value='<?php echo JText::_( 'enter your site URL...' );?>';" onfocus="if(this.value=='<?php echo JText::_( 'enter your site URL...' );?>') this.value='';" />
	
	<?php if ($this->params->get('recaptcha')):?>
	<label class="formRecaptcha"><?php echo JText::_('Enter the two words you see below');?></label>
	<div id="recaptcha"></div>
	<?php endif ?>
	
	<br class="clr" />
	
	<input type="submit" class="button" id="button" value="<?php echo JText::_( 'Submit comment' );?>" />
	
	<span id="formLog"></span>
	
	<input type="hidden" name="option" value="com_k2" />
	<input type="hidden" name="view" value="item" />
	<input type="hidden" name="task" value="comment" />
	<input type="hidden" name="itemID" value="<?php echo JRequest::getInt('id'); ?>" />
</form>
