/**
 * @version		$Id: k2.mootools.js 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

window.addEvent('domready', function(){
	if($('k2ToggleSidebar')){	
		$('k2ToggleSidebar').addEvent('click', function(){
			$('adminFormK2Sidebar').setStyle('display', $('adminFormK2Sidebar').getStyle('display') != 'none' ? 'none' : '')
		});
	}
});
