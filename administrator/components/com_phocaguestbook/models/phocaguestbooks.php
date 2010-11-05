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

jimport('joomla.application.component.model');


class PhocaGuestbookCpModelPhocaGuestbooks extends JModel
{

	var $_data = null;
	var $_total = null;
	var $_pagination = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe;
		
		$context			= 'com_phocaguestbook.phocaguestbook.list.';
	
		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
		
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		
		
		
		return $this->_data;
	}

	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT a.*, cc.title AS guestbook, u.name AS editor '
			. ' FROM #__phocaguestbook_items AS a '
			. ' LEFT JOIN #__phocaguestbook_books AS cc ON cc.id = a.catid '
			. ' LEFT JOIN #__users AS u ON u.id = a.checked_out '
			. $where
			. $orderby
		;

		return $query;
	}
	
	

	function _buildContentOrderBy()
	{
		global $mainframe;
		$context			= 'com_phocaguestbook.phocaguestbook.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY catid, a.ordering '.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , catid, a.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		global $mainframe;
		$context			= 'com_phocaguestbook.phocaguestbook.list.';

		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',				'word' );
	//	$filter_catid		= $mainframe->getUserStateFromRequest( $option.'filter_catid',		'filter_catid',		0,				'int' );
		$filter_guestbook	= $mainframe->getUserStateFromRequest( $context.'filter_guestbook',	'filter_guestbook',	0,				'int' );
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $context.'search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		$where = array();

	/*	if ($filter_catid > 0) {
			$where[] = 'a.catid = '.(int) $filter_catid;
		}*/
		if ($filter_guestbook > 0) {
			$where[] = 'a.catid = '.(int) $filter_guestbook;
		}
		
		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'a.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'a.published = 0';
			}
		}

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}
}
?>