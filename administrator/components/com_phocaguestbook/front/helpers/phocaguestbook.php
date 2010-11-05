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

class PhocaguestbookHelper
{
	function setTinyMCEJS()
	{
		$js = "\t<script type=\"text/javascript\" src=\"".JURI::root()."plugins/editors/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>\n";
		return $js;
	}
	
	function setCaptchaReloadJS()
	{
	/*	$js = "\t". '<script type="text/javascript">function reloadCaptcha() {    var capObj = document.getElementById(\'phocacaptcha\');    if (capObj) {        capObj.src = capObj.src +            (capObj.src.indexOf(\'?\') > -1 ? \'&\' : \'?\') + Math.random();    }} </script>' . "\n";
		*/
		$js  = "\t". '<script type="text/javascript">'."\n".'var pcid = 0;'."\n"
		     . 'function reloadCaptcha() {' . "\n"
			 . 'now = new Date();' . "\n"
			 . 'var capObj = document.getElementById(\'phocacaptcha\');' . "\n"
			 . 'if (capObj) {' . "\n"
			 . 'capObj.src = capObj.src + (capObj.src.indexOf(\'?\') > -1 ? \'&amp;pcid[\'+ pcid +\']=\' : \'?pcid[\'+ pcid +\']=\') + Math.ceil(Math.random()*(now.getTime()));' . "\n"
			 . 'pcid++;' . "\n"
			 . ' }' . "\n"
			 . '}'. "\n"
			 . '</script>' . "\n";
			
			return $js;
	}
	
	
	function displaySimpleTinyMCEJS($displayPath = 0) {

		
	
		$js =	'<script type="text/javascript">' . "\n";
		$js .= 	 'tinyMCE.init({'. "\n"
					.'mode : "textareas",'. "\n"
					.'theme : "advanced",'. "\n"
					.'language : "en",'. "\n"
					.'plugins : "emotions",'. "\n"
					.'editor_selector : "mceEditor",'. "\n"					
					.'theme_advanced_buttons1 : "bold, italic, underline, separator, strikethrough, justifyleft, justifycenter, justifyright, justifyfull, bullist, numlist, undo, redo, link, unlink, separator, emotions",'. "\n"
					.'theme_advanced_buttons2 : "",'. "\n"
					.'theme_advanced_buttons3 : "",'. "\n"
					.'theme_advanced_toolbar_location : "top",'. "\n"
					.'theme_advanced_toolbar_align : "left",'. "\n";
		if ($displayPath == 1) {
			$js .= 'theme_advanced_path_location : "bottom",'. "\n";
		}
		$js .=		 'extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
});' . "\n";
		$js .=	'</script>';
		return $js;

	}
	
	function displayTextArea($name='content',$content='', $width=50, $height=50, $col=10, $row=10, $buttons = false)
	{
		if (is_numeric( $width )) {
			$width .= 'px';
		}
		if (is_numeric( $height )) {
			$height .= 'px';
		}
		$editor  = "<textarea id=\"$name\" name=\"$name\" cols=\"$col\" rows=\"$row\" style=\"width:{$width}; height:{$height};\" class=\"mceEditor\">$content</textarea>\n" . $buttons;

		return $editor;
	}
	
	function wordDelete($string,$length,$end) {
		if (JString::strlen($string) < $length || JString::strlen($string) == $length) {
			return $string;
		} else {
			return JString::substr($string, 0, $length) . $end;
		}
	}
	
	function getDateFormat($dateFormat) {
		switch ($dateFormat) {
			case 1:
			$dateFormat = '%d. %B %Y';
			break;
			case 2:
			$dateFormat = '%d/%m/%y';
			break;
			case 3:
			$dateFormat = '%d. %m. %Y';
			break;
		}
		return $dateFormat;
	}
	
	function getInfo() {
		return base64_decode('PGRpdiBzdHlsZT0idGV4dC1hbGlnbjogcmlnaHQ7IGNvbG9yOiNkM2QzZDM7Ij5Qb3dlcmVkIGJ5IDxhIGhyZWY9Imh0dHA6Ly93d3cucGhvY2EuY3oiIHN0eWxlPSJ0ZXh0LWRlY29yYXRpb246IG5vbmU7IiB0YXJnZXQ9Il9ibGFuayIgdGl0bGU9IlBob2NhLmN6Ij5QaG9jYTwvYT4gPGEgaHJlZj0iaHR0cDovL3d3dy5waG9jYS5jei9waG9jYWd1ZXN0Ym9vayIgc3R5bGU9InRleHQtZGVjb3JhdGlvbjogbm9uZTsiIHRhcmdldD0iX2JsYW5rIiB0aXRsZT0iUGhvY2EgR3Vlc3Rib29rIj5HdWVzdGJvb2s8L2E+PC9kaXY+');
	}
	
	function isRegisteredUser($registeredUsersOnly = 1, $userId) {
		if ($registeredUsersOnly == 1) {
			if ( $userId > 0 ) {
				$registeredUsersOnly = 1;// display form - user is registered, registration required
			} else {
				$registeredUsersOnly = 0;// display no form - user is not registered, registration is required
			}
		} else {
			$registeredUsersOnly = 1;// user is not registered, registration is NOT required - care all as registered
		}
		return $registeredUsersOnly;
	}
	

	function isURLAddress($url) {
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
	
	
}
?>