<?php
defined('_JEXEC') or die('Restricted access');
$ordering = ($this->lists['order'] == 'a.ordering');//Ordering allowed ?
JHTML::_('behavior.tooltip');
?>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm">
	<table>
		<tr>
			<td align="left" width="100%"><?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->lists['state'];
				?>
			</td>
		</tr>
	</table>

	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
					<th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
					
					<th class="title" width="74%"><?php echo JHTML::_('grid.sort',  'Title', 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					
					<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Published', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="14%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
						<?php echo JHTML::_('grid.order',  $this->items ); ?></th>
					<th width="7%">
					<?php echo JHTML::_('grid.sort',   'Access', 'groupname', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
					<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'ID', 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
					$row = &$this->items[$i];
					$link 	= JRoute::_( 'index.php?option=com_phocaguestbook&controller=phocaguestbookb&task=edit&cid[]='. $row->id );
					$checked 	= JHTML::_('grid.checkedout', $row, $i );
					$published 	= JHTML::_('grid.published', $row, $i );
					$access 	= JHTML::_('grid.access',   $row, $i );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
					<td><?php echo $checked; ?></td>
					
					<td>
						<?php
						if (  JTable::isCheckedOut($this->user->get ('id'), $row->checked_out ) ) {
							echo $row->title;
						} else {
						?>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit Phoca Guestbook Guestbook' ); ?>">
								<?php echo $row->title; ?></a>
						<?php
						}
						?>
					</td>
					
					<td align="center"><?php echo $published;?></td>
					<td class="order">
						<span><?php echo $this->pagination->orderUpIcon( $i, ($row->section == @$rows[$i-1]->section),'orderup', 'Move Up', $ordering ); ?></span>
						<span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->section == @$rows[$i+1]->section), 'orderdown', 'Move Down', $ordering ); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
					</td>
					<td align="center">
					<?php echo $access;?>
				</td>

					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
				}
			?>
			</tbody>
		</table>
	</div>

<input type="hidden" name="controller" value="phocaguestbookb" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>