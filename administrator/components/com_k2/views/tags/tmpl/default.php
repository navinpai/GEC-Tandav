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

<form action="index.php" method="post" name="adminForm">
  <table width="100%">
    <tr>
      <td align="left" width="50%"><?php echo JText::_('Filter:'); ?>
        <input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_('Filter by name'); ?>"/>
        <button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='-1';this.form.submit();"><?php echo JText::_('Reset'); ?></button></td>
      <td align="right" width="50%"><?php echo $this->lists['state']; ?></td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th width="20"> # </th>
        <th width="20"> <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
        <th class="title"> <?php echo JHTML::_('grid.sort',   JText::_('Name'), 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th width="5%" nowrap="nowrap"> <?php echo JHTML::_('grid.sort', JText::_('Published'), 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
        <th width="5%" nowrap="nowrap"> <?php echo JHTML::_('grid.sort', JText::_('ID'), 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> </th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="5"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <?php
      $k = 0; $i = 0;
			foreach ($this->rows as $row) :
				$row->checked_out=0;
				$checked 	= JHTML::_('grid.checkedout', $row, $i );
				$published = JHTML::_('grid.published', $row, $i );
				$link = JRoute::_('index.php?option=com_k2&view=tag&cid='.$row->id);
			?>
      <tr class="<?php echo "row$k"; ?>">
        <td width="20" align="center"><?php echo $i+1; ?></td>
        <td width="20" align="center"><?php echo $checked; ?></td>
        <td><a href="<?php echo $link; ?>"><?php echo $row->name;?></a></td>
        <td align="center"><?php echo $published;?></td>
        <td align="center"><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php echo JHTML::_( 'form.token' );?>
</form>
