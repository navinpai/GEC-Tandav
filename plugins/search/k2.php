<?php
/**
 * @version		$Id: k2.php 306 2010-01-11 16:09:17Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$mainframe->registerEvent('onSearch', 'plgSearchItems');
$mainframe->registerEvent('onSearchAreas', 'plgSearchItemsAreas');

JPlugin::loadLanguage('plg_search_k2', JPATH_ADMINISTRATOR);

function & plgSearchItemsAreas() {
	static $areas = array('k2'=>'K2 Items');
	return $areas;
}

function plgSearchItems($text, $phrase = '', $ordering = '', $areas = null) {

	$mainframe = &JFactory::getApplication();

	$db = &JFactory::getDBO();
	$user = &JFactory::getUser();
	$aid = $user->get('aid');

	require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_search'.DS.'helpers'.DS.'search.php');
	require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');

	$searchText = $text;
	if (is_array($areas)) {
		if (!array_intersect($areas, array_keys(plgSearchItemsAreas()))) {
			return array();
		}
	}

	$plugin = &JPluginHelper::getPlugin('search', 'k2');
	$pluginParams = new JParameter($plugin->params);

	$limit = $pluginParams->def('search_limit', 50);

	$text = trim($text);
	if ($text == '') {
		return array();
	}

	$rows = array();

	if ($limit> 0){

		$query = "SELECT i.title AS title,
	    i.metadesc, 
	    i.metakey, 
	    c.name as section, 
	    i.image_caption, 
	    i.image_credits, 
	    i.video_caption, 
	    i.video_credits, 
	    i.extra_fields_search,";
		if($pluginParams->get('search_tags'))
		$query.=" tags.name as tag,";

		$query.=" i.created,
    	CONCAT(i.introtext, i.fulltext) AS text, 
    	CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(':', i.id, i.alias) ELSE i.id END as slug, 
    	CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as catslug  
    	FROM #__k2_items AS i INNER JOIN #__k2_categories AS c ON c.id=i.catid AND c.access <= ".$user->get('gid');
		if($pluginParams->get('search_tags')){
			$query.=" LEFT JOIN #__k2_tags_xref tags_xref ON tags_xref.itemID = i.id";
			$query.=" LEFT JOIN #__k2_tags tags ON tags.id = tags_xref.tagID";
		}

		$query.=" WHERE MATCH(i.title, i.introtext, i.`fulltext`";
		if($pluginParams->get('search_tags'))
		$query.=",tags.name";
			
		$query.=",i.extra_fields_search,i.image_caption,i.image_credits,i.video_caption,i.video_credits,i.metadesc,i.metakey)";

		if($phrase=='exact'){
			$text = $db->Quote('"'.$db->getEscaped($text, true).'"', false);
			$query.= " AGAINST ({$text}  IN BOOLEAN MODE)";
		}
		else {
			$text = $db->Quote($db->getEscaped($text, true), false);
			$query.= " AGAINST ({$text})";
		}

		$query.=" AND i.trash = 0
	    AND i.published = 1 
	    AND i.access <= ".$aid." 
	    AND c.published = 1 
	    AND c.access <= ".$aid."
	    AND c.trash = 0
	    GROUP BY i.id ";

		switch ($ordering) {
			case 'oldest':
				$query.= 'ORDER BY i.created ASC';
				break;

			case 'popular':
				$query.= 'ORDER BY i.hits DESC';
				break;

			case 'alpha':
				$query.= 'ORDER BY i.title ASC';
				break;

			case 'category':
				$query.= 'ORDER BY c.name ASC, i.title ASC';
				break;

			case 'newest':
			default:
				$query.= 'ORDER BY i.created DESC';
				break;
		}

		$db->setQuery($query, 0, $limit);
		$list = $db->loadObjectList();
		$limit -= count($list);

		if (isset($list)) {
			foreach ($list as $key=>$item) {
				$list[$key]->href = JRoute::_(K2HelperRoute::getItemRoute($item->slug, $item->catslug));

			}
		}
		$rows[] = $list;


	}

	$results = array();
	if (count($rows)) {
		foreach ($rows as $row) {
			$new_row = array();
			foreach ($row as $key=>$item) {
				$item->browsernav = '';
				$check = array('text', 'title', 'metakey', 'metadesc', 'section', 'image_caption', 'image_credits', 'video_caption', 'video_credits', 'extra_fields_search');
				if($pluginParams->get('search_tags'))
				$check[]='tag';
				if (searchHelper::checkNoHTML($item, $searchText, array('text', 'title', 'metakey', 'metadesc', 'section', 'tag', 'image_caption', 'image_credits', 'video_caption', 'video_credits', 'extra_fields_search'))) {
					$new_row[] = $item;
				}
			}
			$results = array_merge($results, (array) $new_row);
		}
	}

	return $results;
}
