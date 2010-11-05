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
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class PhocaGuestbookModelPhocaGuestbook extends JModel
{
	var $_id 			= null;
	var $_data 			= null;
	var $_total 		= null;
	var $_context 		= 'com_phocaguestbook.posts';

	function __construct(){
		global $mainframe;
		parent::__construct();
		
		$config 			= JFactory::getConfig();		
		$paramsC 			= JComponentHelper::getParams('com_phocaguestbook') ;
		$default_pagination	= $paramsC->get( 'default_pagination', '20' );
		$context			= $this->_context.'.';
	
		// Get the pagination request variables
		$this->setState('limit', $mainframe->getUserStateFromRequest($context .'limit', 'limit', $default_pagination, 'int'));
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
		// In case limit has been changed, adjust limitstart accordingly
		$this->setState('limitstart', ($this->getState('limit') != 0 ? (floor($this->getState('limitstart') / $this->getState('limit')) * $this->getState('limit')) : 0));
		// Get the filter request variables
		$this->setState('filter_order', JRequest::getCmd('filter_order', 'ordering'));
		$this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'ASC'));

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
	}

	function store($data) {	
		$row =& $this->getTable();

		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// First check: no category
		if ((int)$row->catid < 1) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Second check: not existing category
		$categoryExists = $this->_checkGuestbook((int)$row->catid);
		if (!$categoryExists) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Create the timestamp for the date
		$row->date = gmdate('Y-m-d H:i:s');

		// if new item, order last in appropriate group
		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the Phoca gallery table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	function setId($id) {
		// Set category ID and wipe data
		$this->_id			= $id;
		$this->_category	= null;
	}

	function getData(){
		if (empty($this->_data)) {	
			$query = $this->_buildQuery();

			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}
	
	function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new PhocaGuestbookPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}


	function _buildQuery() {
		// We need to get a list of all items in the given category
		$query = 'SELECT *' .
			' FROM #__phocaguestbook_items' .
			' WHERE catid = '.(int) $this->_id.
			' AND published = 1' .
			' ORDER BY ordering DESC';
		return $query;
	}
	
	function getGuestbook() {
		// Load the Category data
		if ($this->_loadGuestbook()) {
			// Initialize some variables
			$user = &JFactory::getUser();

			// Make sure the category is published
			if (!$this->_guestbook->published) {
				JError::raiseError(404, JText::_("Resource Not Found"));
				return false;
			}
			// check whether category access level allows access
			if ($this->_guestbook->access > $user->get('aid', 0)) {
				JError::raiseError(403, JText::_("ALERTNOTAUTH"));
				return false;
			}
		}
		return $this->_guestbook;
	}
	
	function _loadGuestbook() {
		if (empty($this->_guestbook)) {
			// current category info
			$query = 'SELECT c.*,' .
				' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug '.
				' FROM #__phocaguestbook_books AS c' .
				' WHERE c.id = '. (int) $this->_id ;
				//' AND c.section = "com_phocaguestbook"';
			$this->_db->setQuery($query, 0, 1);
			$this->_guestbook = $this->_db->loadObject();
		}
		return true;
	}
	
	function delete($cid = 0) {
		$query = 'DELETE FROM #__phocaguestbook_items'
			. ' WHERE id = '.$cid ;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	function publish($cid = 0, $publish = 1) {
		$query = 'UPDATE #__phocaguestbook_items'
			. ' SET published = '.(int) $publish
			. ' WHERE id = '.$cid
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	function countItem($id = 0) {
		$query = 'SELECT COUNT(id) FROM #__phocaguestbook_items'
			. ' WHERE published = 1'
			. ' AND catid = '.$id;
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $this->_db->loadRow();
	}
	
	function _checkGuestbook($id) {
		
		$query = 'SELECT c.id' .
			' FROM #__phocaguestbook_books AS c' .
			' WHERE c.id = '. (int) $id ;
		$this->_db->setQuery($query, 0, 1);
		$guestbookExists = $this->_db->loadObject();
		if (isset($guestbookExists->id)) {
			return true;
		}
		return false;
	}
}
?>
