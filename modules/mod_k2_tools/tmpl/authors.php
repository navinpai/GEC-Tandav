<?php
/**
 * @version		$Id: authors.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2AuthorsListBlock <?php echo $params->get('moduleclass_sfx'); ?>">
  <ul>
    <?php foreach ($authors as $author): ?>
    <li>
      <?php if ($params->get('authorAvatar')):?>
      <img class="abAuthorAvatar" src="<?php echo $author->avatar;?>" alt="<?php echo $author->name; ?>" />
      <?php endif; ?>
      
      <a class="abAuthorName" href="<?php echo $author->link; ?>">
      	<?php echo $author->name; ?>
      	
      	<?php if ($params->get('authorItemsCounter')):?>
      	<span>(<?php echo $author->items; ?>)</span>
      	<?php endif; ?>
      </a>
      
      <br class="clr" />
      
      <?php if ($params->get('authorLatestItem')):?>
      <a class="abAuthorLatestItem" href="<?php echo $author->latest->link;?>" title="<?php echo $author->latest->title; ?>">
      	<?php echo $author->latest->title; ?>
      </a>
      <?php endif; ?>
      
      <br class="clr" />
      
      <span class="abAuthorCommentsCount">
      	<?php echo $author->latest->numOfComments;?> <?php echo JText::_('comments');?>
      </span>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
