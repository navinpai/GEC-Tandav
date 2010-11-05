<?php
/**
 * @version		$Id: latest.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerLatest extends JController {
	function display() {
		$view = &$this->getView('latest', 'html');
		$model=&$this->getModel('itemlist');
		$view->setModel($model);
		$model=&$this->getModel('item');
		$view->setModel($model);
		parent::display(true);
	}

}
