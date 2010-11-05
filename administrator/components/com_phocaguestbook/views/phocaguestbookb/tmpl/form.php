<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>

<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton, section) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}


			if ( form.title.value == "" ) {
				alert("<?php echo JText::_( 'Guestbook must have a title', true ); ?>");
			} else {
				<?php
				echo $this->editor->save( 'description' ) ; ?>
				submitform(pressbutton);
			}
		}
		</script>

		<form action="index.php" method="post" name="adminForm">

		<div class="col60">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>

					<table class="admintable">
					<tr>
						<td class="key">
							<label for="title" width="100">
								<?php echo JText::_( 'Title' ); ?>:
							</label>
						</td>
						<td colspan="2">
							<input class="text_area" type="text" name="title" id="title" value="<?php echo $this->phocaguestbook->title; ?>" size="50" maxlength="50" title="<?php echo JText::_( 'A long name to be displayed in headings' ); ?>" />
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="alias">
								<?php echo JText::_( 'Alias' ); ?>:
							</label>
						</td>
						<td colspan="2">
							<input class="text_area" type="text" name="alias" id="alias" value="<?php echo $this->phocaguestbook->alias; ?>" size="50" maxlength="255" title="<?php echo JText::_( 'A short name to appear in menus' ); ?>" />
						</td>
					</tr>
					<tr>
						<td width="120" class="key">
							<?php echo JText::_( 'Published' ); ?>:
						</td>
						<td>
							<?php echo $this->lists['published']; ?>
						</td>
					</tr>
				
					<tr>
						<td class="key">
							<label for="ordering">
								<?php echo JText::_( 'Ordering' ); ?>:
							</label>
						</td>
						<td colspan="2">
							<?php echo $this->lists['ordering']; ?>
						</td>
					</tr>
					<tr>
						<td valign="top" class="key">
							<label for="access">
								<?php echo JText::_( 'Access Level' ); ?>:
							</label>
						</td>
						<td>
							<?php echo $this->lists['access']; ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="image">
								<?php echo JText::_( 'Image' ); ?>:
							</label>
						</td>
						<td>
							<?php echo $this->lists['image']; ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="image_position">
								<?php echo JText::_( 'Image Position' ); ?>:
							</label>
						</td>
						<td>
							<?php echo $this->lists['image_position']; ?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
						<script language="javascript" type="text/javascript">
						if (document.forms.adminForm.image.options.value!=''){
							jsimg='../images/stories/' + getSelectedValue( 'adminForm', 'image' );
						} else {
							jsimg='../images/M_images/blank.png';
						}
						document.write('<img src=' + jsimg + ' name="imagelib" width="80" height="80" border="2" alt="<?php echo JText::_( 'Preview', true ); ?>" />');
						</script>
						</td>
					</tr>

				</table>
			</fieldset>

			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Description' ); ?></legend>

				<table class="admintable">
					<tr>
						<td valign="top" colspan="3">
							<?php
							// parameters : areaname, content, width, height, cols, rows, show xtd buttons
							echo $this->editor->display( 'description',  $this->phocaguestbook->description, '550', '300', '60', '20', array('pagebreak', 'readmore') ) ;
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
		<input type="hidden" name="controller" value="phocaguestbookb" />
		</form>

	
