<?php
/**
 * @version		$Id: comments.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerComments extends JController {
    function display() {
        $model = &$this->getModel('comments');
        $model->checkLogin();
        parent::display();
    }
    function publish() {
    
        $model = &$this->getModel('comments');
        $model->checkLogin();
        JRequest::checkToken() or jexit('Invalid Token');
        $model->publish();
    }
    
    function unpublish() {

    
        $model = &$this->getModel('comments');
        $model->checkLogin();
        JRequest::checkToken() or jexit('Invalid Token');
        $model->unpublish();
    }
    
    function remove() {
    
        $model = &$this->getModel('comments');
        $model->checkLogin();
        JRequest::checkToken() or jexit('Invalid Token');
        $model->remove();
    }
    
    function deleteUnpublished() {
        $model = &$this->getModel('comments');
        $model->checkLogin();
        JRequest::checkToken() or jexit('Invalid Token');
        $model->deleteUnpublished();
    }
	
    function save() {
    	$model = &$this->getModel('comments');
        $model->checkLogin();
		JRequest::checkToken() or jexit('Invalid Token');
		$model->save();
		$mainframe->close();
    }

    
}
