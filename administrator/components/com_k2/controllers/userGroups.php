<?php
/**
 * @version		$Id: userGroups.php 306 2010-01-11 16:09:17Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerUserGroups extends JController
{

	function display() {
		JRequest::setVar('view', 'userGroups');
		parent::display();
	}

	function edit() {
		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$mainframe->redirect('index.php?option=com_k2&view=userGroup&cid='.$cid[0]);
	}

	function add() {
		$mainframe = &JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_k2&view=userGroup');
	}

	function remove() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('userGroups');
		$model->remove();
	}

}
