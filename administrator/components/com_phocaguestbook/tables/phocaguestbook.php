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

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');
//jimport('joomla.mail.helper');

class TablePhocaGuestbook extends JTable
{

	var $id 				= null;
	var $catid 				= null;
	var $sid 				= null;
	var $username 			= null;
	var $userid 			= null;
	var $email	 			= null;
	var $homesite			= null;
	var $ip					= null;
	var $title				= null;
	var $content			= null;
	var $date				= null;
	var $published			= null;
	var $checked_out 		= 0;
	var $checked_out_time 	= 0;
	var $ordering 			= null;
	var $params 			= null;

	function __construct(& $db) {
		parent::__construct('#__phocaguestbook_items', 'id', $db);
		//$this->id        = 0;
	}
	
/*	function check()
	{
		global $mainframe;
		
		// Validate user information
		if (trim( $this->title ) == '') {
		//	$this->setError( JText::_( 'PHOCA GUESTBOOK NO TITLE' ) );
			$mainframe->enqueueMessage( JText::_( 'PHOCA GUESTBOOK NO TITLE' ));
			return false;
		}

		if (trim( $this->username ) == '') {
		//	$this->setError( JText::_( 'PHOCA GUESTBOOK NO USERNAME') );
			$mainframe->enqueueMessage( JText::_( 'PHOCA GUESTBOOK NO USERNAME'));
			return false;
		}


		if (eregi( "[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", $this->username)) {
			//$this->setError( JText::sprintf( 'VALID_AZ09', JText::_( 'Username' ), 2 ) );
			$mainframe->enqueueMessage( JText::_( 'PHOCA GUESTBOOK BAD USERNAME'));
			return false;
		}
		
		if (!JMailHelper::isEmailAddress($this->email) ) {
		//	$this->setError( JText::_( 'WARNREG_MAIL' ) );
			$mainframe->enqueueMessage( JText::_( 'PHOCA GUESTBOOK BAD EMAIL') );
			return false;
		}

		// check for existing username
		$query = 'SELECT id'
		. ' FROM #__users '
		. ' WHERE username = ' . $this->_db->Quote($this->username)
		. ' AND id != '. (int) $this->id;
		;
		$this->_db->setQuery( $query );
		$xid = intval( $this->_db->loadResult() );
		if ($xid && $xid != intval( $this->id )) {
		//	$this->setError(  JText::_('WARNREG_INUSE'));
			$mainframe->enqueueMessage( JText::_('PHOCA GUESTBOOK USERNAME EXISTS') );
			return false;
		}


		// check for existing email
		$query = 'SELECT id'
			. ' FROM #__users '
			. ' WHERE email = '. $this->_db->Quote($this->email)
			. ' AND id != '. (int) $this->id
			;
		$this->_db->setQuery( $query );
		$xid = intval( $this->_db->loadResult() );
		if ($xid && $xid != intval( $this->id )) {
		//	$this->setError( JText::_( 'WARNREG_EMAIL_INUSE' ) );
			$mainframe->enqueueMessage( JText::_( 'PHOCA GUESTBOOK EMAIL EXISTS' ) );
			return false;
		}

		return true;
	}*/
}
?>