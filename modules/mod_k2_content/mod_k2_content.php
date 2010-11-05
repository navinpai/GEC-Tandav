<?php
/**
 * @version		$Id: mod_k2_content.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname( __FILE__ ).DS.'helper.php');

$moduleclass_sfx = $params->get('moduleclass_sfx','');
$getTemplate = $params->get('getTemplate','');

$componentParams = & JComponentHelper::getParams('com_k2');

$items = modK2ContentHelper::getItems($params);

if(count($items)){
	require(JModuleHelper::getLayoutPath('mod_k2_content', $getTemplate.DS.'default'));
}
