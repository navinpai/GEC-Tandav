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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );

class PhocaGuestbookCpViewPhocaGuestbookb extends JView
{

	function display($tpl = null) {
		global $mainframe;
		
		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}
		
		parent::display($tpl);
	}

	function _displayForm($tpl) {
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();
		$editor =& JFactory::getEditor();	
		
		//Data from model
		$phocaguestbook	=& $this->get('Data');
		
		$lists 	= array();		
		$isNew	= ($phocaguestbook->id < 1);
		JHTML::stylesheet( 'phocaguestbook.css', 'administrator/components/com_phocaguestbook/assets/' );
		
		if ($model->isCheckedOut( $user->get('id') )) {	
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'Phoca Guestbook Guestbook' ), $phocaguestbook->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Edit or Create?
		if (!$isNew) {
			$model->checkout( $user->get('id') );
		} else {
			// initialise new record
			$phocaguestbook->published 		= 1;
			$phocaguestbook->order 			= 0;
			$phocaguestbook->access			= 0;
		}

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, title AS text'
			. ' FROM #__phocaguestbook_books'
			. ' ORDER BY ordering';
		$lists['ordering'] 			= JHTML::_('list.specificordering',  $phocaguestbook, $phocaguestbook->id, $query, 1 );
		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $phocaguestbook->published );
		
		$active =  ( $phocaguestbook->image_position ? $phocaguestbook->image_position : 'left' );
		$lists['image_position'] 	= JHTML::_('list.positions',  'image_position', $active, NULL, 0, 0 );
		// Imagelist
		$lists['image'] 			= JHTML::_('list.images',  'image', $phocaguestbook->image );
		// build the html select list for the group access
		$lists['access'] 			= JHTML::_('list.accesslevel',  $phocaguestbook );
	
		//clean gallery data
		jimport('joomla.filter.output');
		JFilterOutput::objectHTMLSafe( $phocaguestbook, ENT_QUOTES, 'description' );

		//Params
		#$file 	= JPATH_COMPONENT.DS.'models'.DS.'phocaguestbook.xml';
		#$params = new JParameter( $phocaguestbook->params, $file );
			
		$this->assignRef('editor', $editor);
		$this->assignRef('lists', $lists);
		$this->assignRef('phocaguestbook', $phocaguestbook);
	
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
		$this->_setToolbar($isNew);
	}
	
	function _setToolbar($isNew) {
	
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Phoca Guestbook Guestbook' ).': <small><small>[ ' . $text.' ]</small></small>', 'guestbook' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::help( 'screen.phocaguestbook', true );
	}
}
?>
