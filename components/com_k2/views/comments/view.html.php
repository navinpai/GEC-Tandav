<?php
/**
 * @version		$Id: view.html.php 307 2010-01-11 20:58:54Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2ViewComments extends JView {

    function display($tpl = null) {
        $mainframe = &JFactory::getApplication();
        $user = &JFactory::getUser();
        if ($user->guest) {
            JError::raiseError(403, JText::_("ALERTNOTAUTH"));
        }

        
        JHTML::_('behavior.mootools');
        $document = &JFactory::getDocument();
        $document->addCustomTag('
<!-- K2 by JoomlaWorks (start) -->
		
	<!-- Load Khepri styling -->
	<link rel="stylesheet" href="'.JURI::root().'administrator/templates/system/css/system.css" type="text/css" />
	<link href="'.JURI::root().'administrator/templates/khepri/css/template.css" rel="stylesheet" type="text/css" />
	<!--[if IE 7]>
	<link href="'.JURI::root().'administrator/templates/khepri/css/ie7.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<!--[if lte IE 6]>
	<link href="'.JURI::root().'administrator/templates/khepri/css/ie6.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="'.JURI::root().'administrator/templates/khepri/css/rounded.css" />
	
	<!-- Load K2 styling -->
	<link href="'.JURI::root().'administrator/components/com_k2/css/k2.css" rel="stylesheet" type="text/css" />
	<!--[if IE 7]>
	<link href="'.JURI::root().'administrator/components/com_k2/css/k2_ie7.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<!--[if lte IE 6]>
	<link href="'.JURI::root().'administrator/components/com_k2/css/k2_ie6.css" rel="stylesheet" type="text/css" />
	<![endif]-->
		
<!-- K2 by JoomlaWorks (end) -->
				');
		
        $model = &$this->getModel();
        $comments = $model->getUserComments($user->id);
		
        $total = $model->countUserComments($user->id);
		
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $limit = $mainframe->getUserStateFromRequest($option.$view.'.limit', 'limit', 10, 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
        $filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'c.id', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');
        $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
        $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
        $search = JString::strtolower($search);

        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);

        $lists = array();
        $lists['search'] = $search;
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        
        $filter_state_options[] = JHTML::_('select.option', -1, JText::_('-- Select State --'));
        $filter_state_options[] = JHTML::_('select.option', 1, JText::_('Published'));
        $filter_state_options[] = JHTML::_('select.option', 0, JText::_('Unpublished'));
        $lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', 'onchange="this.form.submit();"', 'value', 'text', $filter_state);
        
        $this->assignRef('lists', $lists);
        $this->assignRef('rows', $comments);
        $this->assignRef('pagination', $pagination);
		$this->setLayout('default');

        
        parent::display($tpl);
    }
 
}
