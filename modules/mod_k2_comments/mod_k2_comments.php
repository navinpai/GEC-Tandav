<?php
/**
 * @version		$Id: mod_k2_comments.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname( __FILE__ ).DS.'helper.php');

$moduleclass_sfx = $params->get('moduleclass_sfx','');
$module_usage = $params->get('module_usage','0');

$componentParams = & JComponentHelper::getParams('com_k2');

switch($module_usage) {
	case '0':
	$comments = modK2CommentsHelper::getLatestComments($params);
	require (JModuleHelper::getLayoutPath('mod_k2_comments', 'comments'));
	break;

	case '1':
	$commenters = modK2CommentsHelper::getTopCommenters($params);
	require (JModuleHelper::getLayoutPath('mod_k2_comments', 'commenters'));
	break;
}
