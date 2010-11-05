<?php
/**
 * @version		$Id: view.html.php 318 2010-01-15 02:18:54Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2ViewUserGroup extends JView
{

	function display($tpl = null) {
	
		JHTML::_( 'behavior.tooltip' );
		JRequest::setVar('hidemainmenu', 1);
		$model = & $this->getModel();
		$userGroup = $model->getData();
		JFilterOutput::objectHTMLSafe( $userGroup );
		$this->assignRef('row', $userGroup);
		
		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'userGroup.xml');
		$form->loadINI($userGroup->permissions);
		$this->assignRef('form', $form);
		
		$appliedCategories=$form->get('categories');
		$this->assignRef('categories', $appliedCategories);
		
		$lists = array ();
		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel= new K2ModelCategories;
		$categories = $categoriesModel->categoriesTree(NULL, true);
		$categories_options=@array_merge($categories_option, $categories);
		$lists['categories'] = JHTML::_('select.genericlist', $categories, 'params[categories][]', 'multiple="multiple" style="width:90%;" size="15"', 'value', 'text',$appliedCategories);
		$lists['inheritance'] = JHTML::_('select.booleanlist', 'params[inheritance]', NULL, $form->get('inheritance'));
		$this->assignRef('lists',$lists);
		(JRequest::getInt('cid'))? $title = JText::_('Edit user group') : $title = JText::_('Add user group');
		JToolBarHelper::title(JText::_($title));
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	
		parent::display($tpl);
	}

}
