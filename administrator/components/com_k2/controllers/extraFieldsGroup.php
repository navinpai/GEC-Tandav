<?php
/**
 * @version		$Id: extraFieldsGroup.php 306 2010-01-11 16:09:17Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerExtraFieldsGroup extends JController
{

	function display() {
		JRequest::setVar('view', 'extraFieldsGroup');
		$model = & $this->getModel('extraFields');
		$view = & $this->getView('extraFieldsGroup', 'html');
		$view->setModel($model, true);
		parent::display();
	}

	function save() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('extraFields');
		$view = & $this->getView('extraFieldsGroup', 'html');
		$view->setModel($model, true);
		$model->saveGroup();
	}


	function apply() {
		$this->save();
	}

	function cancel() {
		$mainframe = &JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_k2&view=extraFieldsGroups');
	}

}
