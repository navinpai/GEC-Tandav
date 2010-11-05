<?php
defined('_JEXEC') or die('Restricted access');
$user 		=& JFactory::getUser();//user
$ordering 	= ($this->lists['order'] == 'a.ordering');//Ordering allowed ?
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
				echo $this->lists['guestbook'];
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
					
					<th class="title" width="48%"><?php echo JHTML::_('grid.sort',  'Title', 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					
					<th class="title" width="12%"><?php echo JHTML::_('grid.sort',  'Name', 'a.username', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					
					<th class="title" width="12%"><?php echo JHTML::_('grid.sort',  'Content', 'a.content', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					
					<th class="title" width="7%"><?php echo JHTML::_('grid.sort',  'IP', 'a.ip', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					
					<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Published', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="14%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
						<?php echo JHTML::_('grid.order',  $this->items ); ?></th>
					<th width="12%"  class="title">
						<?php echo JHTML::_('grid.sort',  'Guestbook', 'guestbook', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
					<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'ID', 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
					$row = &$this->items[$i];
					$link 	= JRoute::_( 'index.php?option=com_phocaguestbook&controller=phocaguestbook&task=edit&cid[]='. $row->id );
					$checked 	= JHTML::_('grid.checkedout', $row, $i );
					$published 	= JHTML::_('grid.published', $row, $i );
					$row->cat_link 	= JRoute::_( 'index.php?option=com_phocaguestbook&controller=phocaguestbookb&task=edit&cid[]='. $row->catid );
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
							<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit Phoca Guestbook Item' ); ?>">
								<?php echo $row->title; ?></a>
						<?php
						}
						?>
					</td>
					
					<td align="center"><?php echo $row->username ?></td>
					
					<td align="left"><?php
					$row->content = JFilterOutput::cleanText(strip_tags($row->content));
					echo PhocaguestbookHelper::wordDelete($row->content, 60, '...')
					?></td>
					
					<td align="center"><?php echo $row->ip ?></td>
					
					<td align="center"><?php echo $published;?></td>
					<td class="order">
						<span><?php echo $this->pagination->orderUpIcon( $i, ($row->catid == @$this->items[$i-1]->catid),'orderup', 'Move Up', $ordering ); ?></span>
						<span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->catid == @$this->items[$i+1]->catid), 'orderdown', 'Move Down', $ordering ); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
					</td>
					<td><a href="<?php echo $row->cat_link; ?>" title="<?php echo JText::_( 'Edit Phoca Guestbook Guestbook' ); ?>"><?php echo $row->guestbook; ?></a>
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

<input type="hidden" name="controller" value="phocaguestbook" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>
