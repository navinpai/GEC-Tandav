<?php
/**
 * @version		$Id: admin.k2.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
$user = & JFactory::getUser();
$view = JRequest::getWord('view', 'cpanel');

if(($user->gid <= 23) && (
		$view=='extraField' ||
		$view=='extraFields' ||
		$view=='extraFieldsGroup' ||
		$view=='extraFieldsGroups' ||
		$view=='user' ||
		$view=='users' ||
		$view=='userGroup' ||
		$view=='userGroups' ||
		$view=='info'
		)
	)
	{
		JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
	}

$document = & JFactory::getDocument();

// CSS
$document->addStyleSheet(JURI::base().'components/com_k2/css/k2.css');
$document->addCustomTag('
<!--[if IE 7]>
<link href="'.JURI::base().'components/com_k2/css/k2_ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if lte IE 6]>
<link href="'.JURI::base().'components/com_k2/css/k2_ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
');

// JS
$document->addScript(JURI::base().'components/com_k2/lib/simpletabs_1.3.packed.js');
//$document->addScript(JURI::base().'components/com_k2/js/k2.js'); // Core JS
$document->addScript(JURI::base().'components/com_k2/js/k2.mootools.js'); // Mootools based JS

?>
<?php if($view!='item' && JRequest::getWord('task')!='tag'):?>
<div id="k2AdminContainer" class="K2AdminView<?php echo ucfirst($view); ?>">
<?php endif;?>

	<?php
	$controller = JRequest::getWord('view', 'cpanel');
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
	$classname = 'K2Controller'.$controller;
	$controller = new $classname();
	$controller->registerTask('saveAndNew', 'save');
	$controller->execute(JRequest::getWord('task'));
	$controller->redirect();
	?>
</div>

<?php if($view!='item' && JRequest::getWord('task')!='tag'):?>
<div id="k2AdminFooter">
	<?php echo JText::_('K2_COPYRIGHTS'); ?>
</div>
<?php endif;?>
