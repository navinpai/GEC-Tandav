<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementPhocaGuestbook extends JElement
{

	var	$_name = 'PhocaGuestbook';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT a.title AS text, a.id AS value'
		. ' FROM #__phocaguestbook_books AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$guestbooks = $db->loadObjectList();
		array_unshift($guestbooks, JHTML::_('select.option', '0', '- '.JText::_('Select Guestbook').' -', 'value', 'text'));

		return JHTML::_('select.genericlist',  $guestbooks, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name );
	}
}
?>