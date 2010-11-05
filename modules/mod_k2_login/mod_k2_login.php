<?php
/**
 * @version		$Id: mod_k2_login.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname( __FILE__ ).DS.'helper.php');

$moduleclass_sfx = $params->get('moduleclass_sfx', '');

JHTML::_('behavior.mootools');
JHTML::_('behavior.modal');

$type 	= modK2LoginHelper::getType();
$return	= modK2LoginHelper::getReturnURL($params, $type);
$user		= &JFactory::getUser();

if ($user->guest){
	require(JModuleHelper::getLayoutPath('mod_k2_login', 'login'));
} else {
	$user->profile = modK2LoginHelper::getProfile($params);
	$user->numOfComments = modK2LoginHelper::countUserComments($user->id);
	require(JModuleHelper::getLayoutPath('mod_k2_login', 'userblock'));
}
