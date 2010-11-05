<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Guestbook
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.folder' );

function com_install(){
	$message = '<p>Please select if you want to Install or Upgrade Phoca Guestbook component. Click Install for new Phoca Guestbook installation. If you click on Install and some previous Phoca Guestbook version is installed on your system, all Phoca Guestbook data stored in database will be lost. If you click on Uprade, previous Phoca Guestbook data stored in database will be not removed.</p>';
	
	?><div style="padding:20px;border:1px solid #b36b00;background:#fff">
		<a style="text-decoration:underline" href="http://www.phoca.cz/" target="_blank"><?php
			echo  JHTML::_('image.site', 'icon-phoca-logo.png', 'components/com_phocaguestbook/assets/images/', NULL, NULL, 'Phoca.cz');
		?></a>
		<div style="position:relative;float:right;">
			<?php echo  JHTML::_('image.site', 'logo-phoca.png', 'components/com_phocaguestbook/assets/images/', NULL, NULL, 'Phoca.cz');?>
		</div>
		<p>&nbsp;</p>
		<?php echo $message; ?>
		<div style="clear:both">&nbsp;</div>
		<div style="text-align:center"><center><table border="0" cellpadding="20" cellspacing="20">
			<tr>
				<td align="center" valign="middle">
					<a href="index.php?option=com_phocaguestbook&amp;controller=phocaguestbookinstall&amp;task=install"><?php
					echo JHTML::_('image.site',  'install.png', '/components/com_phocaguestbook/assets/images/', NULL, NULL, 'Install' );
					?></a>
				</td>
				
				<td align="center" valign="middle">
					<a href="index.php?option=com_phocaguestbook&amp;controller=phocaguestbookinstall&amp;task=upgrade"><?php
					echo JHTML::_('image.site',  'upgrade.png', '/components/com_phocaguestbook/assets/images/', NULL, NULL, 'Upgrade' );
					?></a>
				</td>
			</tr>
		</table></center></div>
		
		<p>&nbsp;</p><p>&nbsp;</p>
		<p>
		<a href="http://www.phoca.cz/phocaguestbook/" target="_blank">Phoca Guestbook Main Site</a><br />
		<a href="http://www.phoca.cz/documentation/" target="_blank">Phoca Guestbook User Manual</a><br />
		<a href="http://www.phoca.cz/forum/" target="_blank">Phoca Guestbook Forum</a><br />
		</p>
		
		<p>&nbsp;</p>
		<p><center><a style="text-decoration:underline" href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></center></p>		
<?php	
}
?>