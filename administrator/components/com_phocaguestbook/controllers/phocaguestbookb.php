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

class PhocaGuestbookCpControllerPhocaGuestbookb extends PhocaGuestbookCpController
{
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply'  , 'save' );
		$this->registerTask( 'accesspublic', 'accessMenu');
		$this->registerTask( 'accessregistered', 'accessMenu');
		$this->registerTask( 'accessspecial', 'accessMenu');

	}
	
	function edit()
	{
		
		JRequest::setVar( 'view', 'phocaguestbookb' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();

		// Checkin the Phoca gallery
		$model = $this->getModel( 'phocaguestbookb' );
		$model->checkout();
	}

	function save()
	{
		$post					= JRequest::get('post');
		$cid					= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['description']	= JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post['id'] 	= (int) $cid[0];

		$model = $this->getModel( 'phocaguestbookb' );
		
		switch ( JRequest::getCmd('task') )
		{
			case 'apply':
				$id	= $model->store($post);//you get id and you store the table data
				if ($id && $id > 0) {
					$msg = JText::_( 'Changes to Phoca Guestbook Guestbook Saved' );
					//$id		= $model->store($post);
				} else {
					$msg = JText::_( 'Error Saving Phoca Guestbook Guestbook' );
					$id		= $post['id'];
				}
				$this->setRedirect( 'index.php?option=com_phocaguestbook&controller=phocaguestbookb&task=edit&cid[]='. $id, $msg );
				break;

			case 'save':
			default:
				if ($model->store($post)) {
					$msg = JText::_( 'Phoca Guestbook Guestbook Saved' );
				} else {
					$msg = JText::_( 'Error Saving Phoca Guestbook Guestbook' );
				}
				$this->setRedirect( 'index.php?option=com_phocaguestbook&view=phocaguestbookbs', $msg );
				break;
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
	}
	
	function accessMenu()
	{
		$post			= JRequest::get('post');
		$cid			= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$access			= $post['task'];
		
		switch ($access)
		{
			case 'accessregistered':
			$access_id= 1;
			break;

			case 'accessspecial':
			$access_id= 2;
			break;
			
			case 'accesspublic':
			default:
			$access_id= 0;
			break;
		}
		
		$model = $this->getModel( 'phocaguestbookb' );

		$model->accessmenu($cid[0],$access_id);
		$model->checkin();
		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookbs';
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel( 'phocaguestbookb' );
		if(!$model->delete($cid))
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
			$msg = JText::_( 'Error Deleting Phoca Guestbook Guestbook' );
		}
		else {
			$msg = JText::_( 'Phoca Guestbook Guestbook Deleted' );
		}

		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookbs';
		$this->setRedirect( $link, $msg );
	}

	function publish()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('phocaguestbookb');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookbs';
		$this->setRedirect($link);
	}

	function unpublish()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('phocaguestbookb');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookbs';
		$this->setRedirect($link);
	}

	function cancel()
	{
		$model = $this->getModel( 'phocaguestbookb' );
		$model->checkin();

		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookbs';
		$this->setRedirect( $link );
	}

	function orderup()
	{
		$model = $this->getModel( 'phocaguestbookb' );
		$model->move(-1);

		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookbs';
		$this->setRedirect( $link );
	}

	function orderdown()
	{
		$model = $this->getModel( 'phocaguestbookb' );
		$model->move(1);

		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookbs';
		$this->setRedirect( $link );
	}

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel( 'phocaguestbookb' );
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbookbs';
		$this->setRedirect( $link, $msg  );
	}
}
?>
