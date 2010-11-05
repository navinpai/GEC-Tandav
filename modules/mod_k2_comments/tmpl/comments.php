<?php
/**
 * @version		$Id: comments.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2LatestCommentsBlock <?php echo $params->get('moduleclass_sfx'); ?>">
	<ul>
		<?php if(count($comments)): ?>
		<?php foreach ($comments as $key=>$comment):	?>
		<li class="<?php echo ($key%2) ? "odd" : "even"; ?>">
			<?php if($comment->userImage): ?>
			<img style="width:<?php echo $componentParams->get('commenterImgWidth'); ?>px;height:auto;" class="lcAvatar" src="<?php echo $comment->userImage; ?>" alt="<?php echo $comment->userName; ?>" />
			<?php endif; ?>
			
			<?php if($params->get('commentLink')): ?>
			<a href="<?php echo $comment->link; ?>"><span class="lcComment"><?php echo $comment->commentText; ?></span></a>
			<?php else: ?>
			<span class="lcComment"><?php echo $comment->commentText; ?></span>
			<?php endif; ?>
			
			<br />
			
			<?php if($params->get('commenterName')): ?>
			<span class="lcUsername"><?php echo JText::_('written_by'); ?>
			<?php if (isset($comment->userLink)):?>
			<a href="<?php echo $comment->userLink;?>">
			<?php endif; ?>
			 <?php echo $comment->userName; ?>
			<?php if (isset($comment->userLink)):?>
			</a>
			<?php endif; ?>
			 </span>
			<?php endif; ?>
			
			<?php if($params->get('commentDate')): ?>
			<span class="lcCommentDate">
				<?php if ($params->get('commentDateFormat') == 'relative') :?>
				<?php echo $comment->commentDate;?>
				<?php else:?>
				<?php echo JText::_('on'); ?> <?php echo JHTML::_('date', $comment->commentDate, JText::_('DATE_FORMAT_LC2')); ?>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			
			<br />
			
			<?php if($params->get('itemTitle')): ?>
			<span class="lcItemTitle"><a href="<?php echo $comment->itemLink; ?>"><?php echo $comment->title; ?></a></span>
			<?php endif; ?>
			
			<?php if($params->get('itemCategory')): ?>
			<span class="lcItemCategory">(<a href="<?php echo $comment->catLink; ?>"><?php echo $comment->categoryname; ?></a>)</span>
			<?php endif; ?>
			
			<br class="clr" />
		</li>
		<?php endforeach; ?>
		<?php endif; ?>
		<li class="clearList"></li>
	</ul>
	
	<?php if($params->get('feed')): ?>
	<div class="k2FeedIcon">
		<a href="<?php echo JRoute::_('index.php?option=com_k2&view=itemlist&format=feed&moduleID='.$module->id);?>" title="<?php echo JText::_('Subscribe to this RSS feed'); ?>">
			<span><?php echo JText::_('Subscribe to this RSS feed'); ?></span>
		</a>
		<div class="clr"></div>
	</div>
	<?php endif; ?>
	
</div>
