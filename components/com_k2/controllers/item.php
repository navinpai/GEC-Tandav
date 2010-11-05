<?php
/**
 * @version		$Id: item.php 306 2010-01-11 16:09:17Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2ControllerItem extends JController
{

	function display() {

		$model=&$this->getModel('itemlist');
		$document =& JFactory::getDocument();
		$viewType = $document->getType();
		$view = &$this->getView('item', $viewType);
		$view->setModel($model);
		JRequest::setVar('view', 'item');
		$user = &JFactory::getUser();
		if ($user->guest){
			parent::display(true);
		}
		else {
			parent::display(false);
		}
	}

	function edit() {
	
		$view = & $this->getView('item', 'html');
		$view->setLayout('form');
		$view->edit();
	}
	
	function add() {
	
		$view = & $this->getView('item', 'html');
		$view->setLayout('form');
		$view->edit();
	}
	
	function save() {
		$mainframe = &JFactory::getApplication();
		JRequest::checkToken() or jexit('Invalid Token');
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'item.php');
		$model= new K2ModelItem;
		$model->save(true);
		$mainframe->close();
		
	}
	
	function deleteAttachment() {
	
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'item.php');
		$model= new K2ModelItem;
		$model->deleteAttachment();
	}
	
	function tag() {
	
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'tag.php');
		$model= new K2ModelTag;
		$model->addTag();
	}
	
	function tags() {
	
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'tag.php');
		$model= new K2ModelTag;
		$model->tags();
	}
	
	function download(){
	
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'item.php');
		$model= new K2ModelItem;
		$model->download(true);
	}
	
	function extraFields(){
		
		$mainframe = &JFactory::getApplication();
		$itemID=JRequest::getInt('cid',NULL);

		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
		$catid = JRequest::getVar('id');
		$category = & JTable::getInstance('K2Category', 'Table');
		$category->load($catid);
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'extrafield.php');
		$extraFieldModel= new K2ModelExtraField;
		
		$extraFields = $extraFieldModel->getExtraFieldsByGroup($category->extraFieldsGroup);
		
		$output='<table class="admintable" id="extraFields">';
		$counter=0;
		if (count($extraFields)){
			foreach ($extraFields as $extraField){
				$output.='<tr><td align="right" class="key">'.$extraField->name.'</td>';
				$output.='<td>'.$extraFieldModel->renderExtraField($extraField,$itemID).'</td></tr>';
				$counter++;
			}
		}
		$output.='</table>';
		
		if ($counter==0) $output=JText::_("This category doesn't have assigned extra fields");
					
		echo $output;
		$mainframe->close();
	}

	function checkin(){
		
		$model = & $this->getModel('item');
		$model->checkin();
	}
	
	function vote()	{
		
		$model = & $this->getModel('item');
		$model->vote();
	}
	
	function getVotesNum()	{
		
		$model = & $this->getModel('item');
		$model->getVotesNum();
	}
	
	function getVotesPercentage()	{
		
		$model = & $this->getModel('item');
		$model->getVotesPercentage();
	}
	
	function comment(){
	
		$model = & $this->getModel('item');
		$model->comment();
	}

}
