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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class PhocaGuestbookCpControllerPhocaGuestbookuninstall extends PhocaGuestbookCpController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'remove'  , 'remove' );
		$this->registerTask( 'keep'  , 'keep' );		
	}

	
	
	function remove()
	{		
		$db			=& JFactory::getDBO();
		$db_prefix 	= $db->getPrefix();
		
		$msg_sql = '';
		
		$query =' DROP TABLE IF EXISTS `'.$db_prefix.'phocaguestbook_items`;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msg_sql .= $db->stderr() . '<br />';}
			
		$query=' DROP TABLE IF EXISTS `'.$db_prefix.'phocaguestbook_books`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msg_sql .= $db->stderr() . '<br />';}
		
		
		if ($msg_sql !='')
		{
			$msg = JText::_( 'Phoca Guestbook not succesfully uninstalled' ) . ': <br />' . $msg_sql;
		}
		else
		{
			$msg = JText::_( 'Phoca Guestbook succesfully uninstalled with removing data from database' );
		}
		
		$link = 'index.php?option=com_installer';
		$this->setRedirect($link, $msg);
	}
	
	function keep()
	{
		$msg = JText::_( 'Phoca Guestbook succesfully uninstalled without removing data from database' );
		$link = 'index.php?option=com_installer';
		$this->setRedirect($link, $msg);
	}
}