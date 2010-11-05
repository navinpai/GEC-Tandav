<?php
/**
 * @version		$Id: view.html.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2ViewExtraFieldsGroup extends JView
{

	function display($tpl = null) {
	
		JRequest::setVar('hidemainmenu', 1);
		$model = & $this->getModel();
		$extraFieldsGroup = $model->getExtraFieldsGroup();
		JFilterOutput::objectHTMLSafe( $extraFieldsGroup );
		$this->assignRef('row', $extraFieldsGroup);
		(JRequest::getInt('cid'))? $title = JText::_('Edit Extra Field Group') : $title = JText::_('Add Extra Field Group');
		JToolBarHelper::title($title);
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	
		parent::display($tpl);
	}

}
