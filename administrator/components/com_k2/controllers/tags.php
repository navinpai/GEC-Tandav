<?php
/**
 * @version		$Id: tags.php 306 2010-01-11 16:09:17Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerTags extends JController
{

	function display() {
		JRequest::setVar('view', 'tags');
		parent::display();
	}

	function publish() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('tags');
		$model->publish();
	}

	function unpublish() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('tags');
		$model->unpublish();
	}

	function remove() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('tags');
		$model->remove();
	}

	function add() {
		$mainframe = &JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_k2&view=tag');
	}

	function edit() {
		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$mainframe->redirect('index.php?option=com_k2&view=tag&cid='.$cid[0]);
	}

	function element() {
		JRequest::setVar('view', 'tags');
		JRequest::setVar('layout', 'element');
		parent::display();
	}

}
