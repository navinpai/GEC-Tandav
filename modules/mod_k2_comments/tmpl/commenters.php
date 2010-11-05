<?php
/**
 * @version		$Id: commenters.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2TopCommentersBlock <?php echo $params->get('moduleclass_sfx'); ?>">
	<ul>
		<?php if(count($commenters)): ?>
		<?php foreach ($commenters as $key=>$commenter): ?>
		<li class="<?php echo ($key%2) ? "odd" : "even"; ?>">
			<?php if($params->get('commenterLink')): ?>
			<a class="tcLink" href="<?php echo $commenter->link; ?>">
			<?php endif; ?>

			<?php if($commenter->userImage): ?>
			<img style="width:<?php echo $componentParams->get('commenterImgWidth'); ?>px;height:auto;" class="tcAvatar" src="<?php echo $commenter->userImage; ?>" alt="<?php echo $commenter->userName; ?>" />
			<?php endif; ?>

			<span class="tcUsername"><?php echo $commenter->userName; ?></span>
			
			<?php if($params->get('commenterCommentsCounter')): ?>
			<span class="tcCommentsCounter">(<?php echo $commenter->counter; ?>)</span>
			<?php endif; ?>
			
			<?php if($params->get('commenterLink')): ?>
			</a>
			<?php endif; ?>
						
			<?php if($params->get('commenterLatestComment')): ?>
			<!-- LATEST COMMENT -->
			<a class="tcLatestComment" href="<?php echo $commenter->latestCommentLink;?>"><?php echo $commenter->latestCommentText; ?></a>
			<span class="tcLatestCommentDate"><?php echo JText::_('posted on'); ?> <?php echo JHTML::_('date', $commenter->latestCommentDate, JText::_('DATE_FORMAT_LC2')); ?></span>
			<?php endif; ?>
			
			<br class="clr" />
		</li>
		<?php endforeach; ?>
		<?php endif; ?>
		<li class="clearList"></li>
	</ul>
</div>
