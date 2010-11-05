<?php
/**
 * @version		$Id: element.php 315 2010-01-14 18:49:25Z joomlaworks $
 * @package		K2
 * @author    	JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm">

	<h1><?php echo JText::_('Select categories'); ?></h1>

  <table class="k2AdminTableFilters">
    <tr>
      <td class="k2AdminTableFiltersSearch">
				<?php echo JText::_('Filter:'); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_('Filter by title'); ?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='-1';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
      </td>
      <td class="k2AdminTableFiltersSelects">
      	<?php echo $this->lists['state']; ?>
      </td>
    </tr>
  </table>

  <table class="adminlist">
    <thead>
      <tr>
        <th>#</th>
        <th> <?php echo JHTML::_('grid.sort',   JText::_('Title'), 'c.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Associated extra field groups'), 'extra_fields_group', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Access Level'), 'c.access', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', JText::_('Published'), 'c.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', JText::_('ID'), 'c.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
      </tr>
    </thead>
    <tbody>
  <?php
  $k = 0; $i = 0;	$n = count( $this->rows );
	foreach ($this->rows as $row) :
		$published = JHTML::_('grid.published', $row, $i );
		$access = JHTML::_('grid.access', $row, $i );
		$link = JRoute::_('index.php?option=com_k2&view=category&cid='.$row->id);
		?>
      <tr class="<?php echo "row$k"; ?>">
        <td><?php echo $i+1; ?></td>
        <td><a class="k2ListItemDisabled" title="<?php echo JText::_('Click to add this item'); ?>" onclick="window.parent.jSelectCategory('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""),$row->name); ?>', 'id');"><?php echo $row->treename; ?></a></td>
        <td class="k2Center"><?php echo $row->extra_fields_group; ?></td>
        <td class="k2Center"><?php echo strip_tags($access);?></td>
        <td class="k2Center"><?php echo strip_tags($published,'<img>');?></td>
        <td><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
  <input type="hidden" name="task" value="element" />
  <input type="hidden" name="tmpl" value="component" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <?php echo JHTML::_( 'form.token' );?>
</form>
