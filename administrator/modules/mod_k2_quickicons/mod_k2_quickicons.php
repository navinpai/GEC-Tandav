<?php
/**
 * @version		$Id: mod_k2_quickicons.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// JoomlaWorks reference parameters
$mod_name               = "mod_k2_quickicons";
$mod_copyrights_start   = "\n\n<!-- JoomlaWorks \"K2 QuickIcons\" Module (v2.2) starts here -->\n";
$mod_copyrights_end     = "\n<!-- JoomlaWorks \"K2 QuickIcons\" Module (v2.2) ends here -->\n\n";

// API
$mainframe	= &JFactory::getApplication();
$document 	= &JFactory::getDocument();

// Module parameters
$moduleclass_sfx 	= $params->get('moduleclass_sfx','');
$modCSSStyling		= (int) $params->get('modCSSStyling',1);
$modLogo			= (int) $params->get('modLogo',1);

// QuickIcons include
$quickIconsFile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'views'.DS.'cpanel'.DS.'tmpl'.DS.'default_quickicons.php';

// Append CSS to the document's head
if($modCSSStyling) $document->addStyleSheet(JURI::root().'administrator/modules/'.$mod_name.'/tmpl/css/style.css');

// Output content with template
echo $mod_copyrights_start;
require(JModuleHelper::getLayoutPath($mod_name,'default'));
echo $mod_copyrights_end;
