<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		/*if (form.title.value == ""){
			alert( "<?php echo JText::_( 'Gallery item must have a title', true ); ?>" );
		} else*/ if (form.catid.value == "0"){
			alert( "<?php echo JText::_( 'You must select a guestbook', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>

<style type="text/css">
	table.paramlist td.paramlist_key {
		width: 92px;
		text-align: left;
		height: 30px;
	}
</style>


<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" id="adminForm">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Title' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo $this->phocaguestbook->title;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="pgusername">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="pgusername" id="pgusername" size="32" maxlength="250" value="<?php echo $this->phocaguestbook->username;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="email">
					<?php echo JText::_( 'Email' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="email" id="email" size="32" maxlength="250" value="<?php echo $this->phocaguestbook->email;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="homesite">
					<?php echo JText::_( 'Website' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="homesite" id="homesite" size="32" maxlength="250" value="<?php echo $this->phocaguestbook->homesite;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="ip">
					<?php echo JText::_( 'IP' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<input class="text_area" type="text" name="ip" id="ip" size="32" maxlength="250" value="<?php echo $this->phocaguestbook->ip;?>" />
			</td>
		</tr>
	
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td colspan="2">
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="catid">
					<?php echo JText::_( 'Guestbook' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['guestbook']; ?>
			</td>
		</tr>
		
		<tr>
			<td valign="top" align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( 'Ordering' ); ?>:
				</label>
			</td>
			<td colspan="2">
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
	</table>
	</fieldset>
	
	<fieldset class="adminform">
				<legend><?php echo JText::_( 'Content' ); ?></legend>

				<table class="admintable">
					<tr>
						<td valign="top" colspan="3">
							<?php
							// parameters : areaname, content, width, height, cols, rows, show xtd buttons
							echo $this->editor->display( 'content',  $this->phocaguestbook->content, '550', '300', '60', '20', array('image','pagebreak', 'readmore') ) ;
							?>
						</td>
					</tr>
					</table>
			</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_phocaguestbook" />
<input type="hidden" name="cid[]" value="<?php echo $this->phocaguestbook->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="phocaguestbook" />
</form>
