<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.mail.helper');
jimport('joomla.application.component.model');

class phocaguestbookCpModelphocaguestbook extends JModel
{
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if ($this->_loadData())
		{
			$user = &JFactory::getUser();
			
	/*		// Check to see if the category is published
			if (!$this->_data->cat_pub) {
				JError::raiseError( 404, JText::_("Resource Not Found") );
				return;
			}
			
			// Check whether category access level allows access
			if ($this->_data->cat_access > $user->get('aid', 0)) {
				JError::raiseError( 403, JText::_('ALERTNOTAUTH') );
				return;
			}*/
		}
		else
		{
			$this->_initData();
		}
		return $this->_data;
	}
	
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}

	function checkin()
	{
		if ($this->_id)
		{
			$phocaguestbook = & $this->getTable();
			if(! $phocaguestbook->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}

	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$phocaguestbook = & $this->getTable();
			if(!$phocaguestbook->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
	
	function store($data)
	{
		global $mainframe;
		$user 		=& JFactory::getUser();
		$db 		= JFactory::getDBO();
		
		// Validate user information
		if (eregi( "[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", $data['username'])) {
			//$this->setError( JText::sprintf( 'VALID_AZ09', JText::_( 'Username' ), 2 ) );
			$mainframe->enqueueMessage( JText::_( 'PHOCA GUESTBOOK BAD USERNAME'));
			return false;
		}
		
		if ($data['email'] != '' && !JMailHelper::isEmailAddress($data['email']) ) {
			//$this->setError( JText::_( 'WARNREG_MAIL' ) );
			$mainframe->enqueueMessage( JText::_( 'PHOCA GUESTBOOK BAD EMAIL') );
			return false;
		}
		
/*
		// check for existing username
		$query = 'SELECT id'
		. ' FROM #__users '
		. ' WHERE username = ' . $db->Quote($data['username'])
		. ' AND id != '. (int) $user->id;
		;
		$db->setQuery( $query );
		$xid = intval( $db->loadResult() );
		if ($xid && $xid != intval( $user->id )) {
			//$this->setError(  JText::_('WARNREG_INUSE'));
			$mainframe->enqueueMessage( JText::_('PHOCA GUESTBOOK USERNAME EXISTS') );
			return false;
		}


		// check for existing email
		$query = 'SELECT id'
			. ' FROM #__users '
			. ' WHERE email = '. $db->Quote($data['email'])
			. ' AND id != '. (int) $user->id
			;
		$db->setQuery( $query );
		$xid = intval( $db->loadResult() );
		if ($xid && $xid != intval( $user->id )) {
			//$this->setError( JText::_( 'WARNREG_EMAIL_INUSE' ) );
			$mainframe->enqueueMessage( JText::_( 'PHOCA GUESTBOOK EMAIL EXISTS' ) );
			return false;
		}
*/
		
		$row =& $this->getTable();
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// if new item, order last in appropriate group
		if (!$row->id) {
			//$where = 'catid = ' . (int) $row->catid ;
			$where = '';
			$row->ordering = $row->getNextOrder( $where );
		}

		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $row->id;
	}

	function delete($cid = array())
	{
		$result = false;
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			//Delete it from DB
			$query = 'DELETE FROM #__phocaguestbook_items'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__phocaguestbook_items'
				. ' SET published = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )'
				. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	function move($direction)
	{
		$row =& $this->getTable();
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction, ' published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}


	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		$groupings = array();

		//$catid is null
		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			// track categories
			$groupings[] = $row->catid;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('catid = '.(int) $group);
		}
		return true;
	}
	
	function _loadData()
	{
		if (empty($this->_data))
		{		
			$query = 'SELECT p.* '.	
					' FROM #__phocaguestbook_items AS p' .
					' WHERE p.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$phocaguestbook = new stdClass();
			$phocaguestbook->id				= 0;
			$phocaguestbook->catid			= 0;
			$phocaguestbook->sid			= 0;
			$phocaguestbook->username		= null;
			$phocaguestbook->userid			= null;
			$phocaguestbook->email          = null;
			$phocaguestbook->homesite		= null;
			$phocaguestbook->title			= null;
			$phocaguestbook->content		= 0;
			$phocaguestbook->date			= 0;
			$phocaguestbook->published		= 1;
			$phocaguestbook->checked_out		= 0;
			$phocaguestbook->checked_out_time	= 0;
			$phocaguestbook->ordering			= 0;
			$phocaguestbook->params			= null;
			$this->_data					= $phocaguestbook;
			return (boolean) $this->_data;
		}
		return true;
	}
}
?>