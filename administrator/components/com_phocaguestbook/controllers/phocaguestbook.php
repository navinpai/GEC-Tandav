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

class PhocaGuestbookCpControllerPhocaGuestbook extends PhocaGuestbookCpController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply'  , 'save' );
	}

	function edit()
	{
		
		JRequest::setVar( 'view', 'phocaguestbook' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();

		// Checkin the Phoca gallery
		$model = $this->getModel( 'phocaguestbook' );
		$model->checkout();
	}

	function save()
	{
		global $mainframe;
		$user 		=& JFactory::getUser();
		$db 		= JFactory::getDBO();
		
		$post				= JRequest::get('post');
		$cid				= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] 		= (int) $cid[0];
		$post['content']	= JRequest::getVar( 'content', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post['email']		= JRequest::getVar( 'email', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post['username']	= JRequest::getVar( 'pgusername', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		
		$model = $this->getModel( 'phocaguestbook' );
		
		switch ( JRequest::getCmd('task') )
		{
			case 'apply':		
				$id	= $model->store($post);//you get id and you store the table data
				if ($id && $id > 0) {
					$msg = JText::_( 'Changes to Phoca Guestbook Item Saved' );

				} else {
					$msg = JText::_( 'Error Saving Phoca Guestbook Item' );
					$msg_array	= $mainframe->getMessageQueue();
					$msg		.= $msg_array['message'];
					$id			= $post['id'];
				}
				$this->setRedirect( 'index.php?option=com_phocaguestbook&controller=phocaguestbook&task=edit&cid[]='. $id, $msg );
				break;

			case 'save':
			default:
				if ($model->store($post)) {
					$msg = JText::_( 'Phoca Guestbook Item Saved Admin' );
				} else {
					$msg = JText::_( 'Error Saving Phoca Guestbook Item' );
					$msg_array	= $mainframe->getMessageQueue();
					$msg		.= $msg_array['message'];
				}
				$this->setRedirect( 'index.php?option=com_phocaguestbook&view=phocaguestbooks', $msg );
				break;
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
	}

	function remove()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel( 'phocaguestbook' );
		if(!$model->delete($cid))
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
			$msg = JText::_( 'Error Deleting Phoca Guestbook Item' );
		}
		else {
			$msg = JText::_( 'Phoca Guestbook Item Deleted' );
		}

		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooks';
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

		$model = $this->getModel('phocaguestbook');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooks';
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

		$model = $this->getModel('phocaguestbook');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooks';
		$this->setRedirect($link);
	}

	function cancel()
	{
		$model = $this->getModel( 'phocaguestbook' );
		$model->checkin();

		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooks';
		$this->setRedirect( $link );
	}

	function orderup()
	{
		$model = $this->getModel( 'phocaguestbook' );
		$model->move(-1);

		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooks';
		$this->setRedirect( $link );
	}

	function orderdown()
	{
		$model = $this->getModel( 'phocaguestbook' );
		$model->move(1);

		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooks';
		$this->setRedirect( $link );
	}

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel( 'phocaguestbook' );
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$link = 'index.php?option=com_phocaguestbook&view=phocaguestbooks';
		$this->setRedirect( $link, $msg  );
	}
}
?>
