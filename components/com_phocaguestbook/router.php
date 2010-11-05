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
 
function PhocaguestbookBuildRoute(&$query)
{	
	$segments = array();

	if(isset($query['view'])) 
	{
		if(!isset($query['Itemid'])) {
			$segments[] = $query['view'];
		} 
		
		unset($query['view']);
	};

	if(isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	};

	return $segments;
}

function PhocaguestbookParseRoute($segments)
{
	$vars = array();

	//Get the active menu item
	$menu =& JSite::getMenu();
	$item =& $menu->getActive();
	
	if (is_object($item))
	{
		if (isset($item->query['view']) && $item->query['view'] == 'phocaguestbook' && isset($segments[0]))
		{
			$vars['view']	= 'phocaguestbook';
			$vars['id']		= $segments[0];
		}
	}
	else
	{
		// Count route segments
		$count = count($segments);

		// Check if there are any route segments to handle.
		if ($count)
		{
			if (count($segments[0]) == 1)
			{
				$vars['view']	= 'phocaguestbook';
				$vars['id']	= $segments[$count-1];

			}
			else
			{
				$vars['view']	= 'phocaguestbook';
				$vars['id']	= $segments[$count-1];
			}
		}
	}
	return $vars;
}
?>