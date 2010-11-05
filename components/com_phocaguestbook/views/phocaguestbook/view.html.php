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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');

class PhocaGuestbookViewPhocaGuestbook extends JView
{
	function display($tpl = null) {
		
		global $mainframe;
		$pathway 	= &$mainframe->getPathway();
		$document	= &JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$user 		= &JFactory::getUser();
		$params		= &$mainframe->getParams();
		$tmpl 		= array();
		
		JHTML::stylesheet( 'phocaguestbook.css', 'components/com_phocaguestbook/assets/' );
		
		$tmpl['administrator'] = 0;
		if (strtolower($user->usertype) == strtolower('super administrator') || strtolower($user->usertype) == strtolower('administrator')) {
			$tmpl['administrator'] = 1;
		}
		
		//PARAMS
		$tmpl['captcha_method']			= $params->get( 'captcha_method', 1 );
		$tmpl['enable_editor']			= $params->get( 'enable_editor', 1 );
		$tmpl['table_width']			= $params->get( 'table_width', 400 );
		$tmpl['editor_width']			= $params->get( 'editor_width', 400 );
		$tmpl['editor_height']			= $params->get( 'editor_height', 200 );
		$tmpl['display_form']			= $params->get( 'display_form', 1 );
		$tmpl['date_format'] 			= $params->get( 'date_format','DATE_FORMAT_LC' );
		$tmpl['font_color'] 			= $params->get( 'font_color', '#000000' );
		$tmpl['second_font_color'] 		= $params->get( 'second_font_color', '#dddddd' );
		$tmpl['background_color'] 		= $params->get( 'background_color', '#C8DFF9' );
		$tmpl['border_color'] 			= $params->get( 'border_color','#E6E6E6' );
		$tmpl['display_name_form'] 		= $params->get( 'display_name_form', 2 );
		$tmpl['display_email_form']	 	= $params->get( 'display_email_form', 1 );
		$tmpl['display_title_form'] 	= $params->get( 'display_title_form', 2 );
		$tmpl['display_content_form'] 	= $params->get( 'display_content_form', 2 );
		$tmpl['display_website_form'] 	= $params->get( 'display_website_form', 0 );
		$tmpl['display_name'] 			= $params->get( 'display_name', 1 );
		$tmpl['display_email']			= $params->get( 'display_email', 1 );
		$tmpl['display_website']		= $params->get( 'display_website', 1 );
		$tmpl['username_or_name'] 		= $params->get( 'username_or_name', 0 );
		$tmpl['predefined_name'] 		= $params->get( 'predefined_name', '' );
		$tmpl['enable_html_purifier'] 	= $params->get( 'enable_html_purifier', 1 );
		$tmpl['display_path_editor']	= $params->get( 'display_path_editor', 1 );
		
		
		
		$tmpl['date_format']	= PhocaguestbookHelper::getDateFormat($tmpl['date_format']);
		$document->addCustomTag(PhocaguestbookHelper::setCaptchaReloadJS());
		if ($tmpl['enable_editor'] == 1) {
			$document->addCustomTag(PhocaguestbookHelper::setTinyMCEJS());
			$document->addCustomTag(PhocaguestbookHelper::displaySimpleTinyMCEJS($tmpl['display_path_editor']));
		}
		
		// - - - - - - - - - - -
		// Fill the form in case, you get data from post (e.g. user send data, but with no valid captcha
		// We send him back to the form but without lossing data
		
		$post				= JRequest::get( 'post' );
		$post['content']	= JRequest::getVar( 'pgbcontent', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$cid				= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id					= JRequest::getVar( 'id', '', 'get', 'string' );
		$post['catid'] 		= (int) $cid[0];
		
		
		if ((int)$id < 1) {
			echo '<div id="phocaguestbook"><div class="error">'.JText::_('Warning Select Guestbook').'</div></div>';
			return true;
		}
		
		if (isset($post['pgusername'])) { // if not there is other code to solve it - see below
			$post['username']	= $post['pgusername'];
		}
		
		// HTML Purifier - - - - - - - - - - 
		if ($tmpl['enable_html_purifier'] == 0) {
			$filterTags		= '';//preg_split( '#[,\s]+#', trim( ) ); // black list method is used
			$filterAttrs	= '';//preg_split( '#[,\s]+#', trim( ) ); // black list method is used
		
			$filter	= new JFilterInput( $filterTags, $filterAttrs, 1, 1, 1 );
			$post['content']	= $filter->clean( $post['content'] );
		} else {		

			require_once( JPATH_COMPONENT.DS.'assets'.DS.'library'.DS.'HTMLPurifier.auto.php' );
			$configP = HTMLPurifier_Config::createDefault();
			$configP->set('Core', 'TidyFormat', !empty($_REQUEST['tidy']));
			$configP->set('Core', 'DefinitionCache', null);
			$configP->set('HTML', 'Allowed', 'strong,em,p[style],span[style],img[src|width|height|alt|title],li,ul,ol,a[href],u,strike,br');
			$purifier = new HTMLPurifier($configP);
			$post['content'] = $purifier->purify($post['content']);
		}
		
		// - - - - - - - - - -
		// Add username and user e-mail if user is login
		if ($tmpl['username_or_name'] == 1) {
			if ($user->name && trim($user->name !='')) {
				$form_username = $user->name;
			} else {
				$form_username = $tmpl['predefined_name'];
			}
		} else {
			if ($user->username && trim($user->username !='')) {
				$form_username = $user->username;
			} else {
				$form_username = $tmpl['predefined_name'];
			}
		}
		
		if ($user->email && trim($user->email !='')) {
			$form_email = $user->email;
		} else {
			$form_email = '';
		}
		
		// - - - - - - - - - - -
		// !!!! Add content to the fields
		
		// - - - - - - - - - - -
		//Create new object, if user fill not all data, no redirection and he gets the data he added (he doesn't loss it)
		$formdata = new JObject();
		if (isset($post['content']))	{$formdata->set('content', $post['content']);}
		else							{$formdata->set('content', '');}
		if (isset($post['username']))	{$formdata->set('username', $post['username']);}
		else							{$formdata->set('username', $form_username);}
		if (isset($post['email']))		{$formdata->set('email', $post['email']);}
		else							{$formdata->set('email', $form_email);}
		if (isset($post['title']))		{$formdata->set('title', $post['title']);}
		else							{$formdata->set('title', '');}
		//if (isset($post['website']))	{$formdata->set('website', $post['website']);}
		//else							{$formdata->set('website', 'http://');}
		
		if (isset($post['website']))	{$formdata->set('website', $post['website']);}
		else							{
			
			if ($tmpl['display_website'] == 2) {
				$formdata->set('website', 'http://');//required
			} else {
				$formdata->set('website', '');// not required
			}
		}
		
		if ($tmpl['enable_editor'] == 1) {
			$tmpl['editor'] = PhocaguestbookHelper::displayTextArea('pgbcontent',  $formdata->content , (int)$tmpl['editor_width'].'px', (int)$tmpl['editor_height'].'px', '60', '80', false );
		} else {
			$tmpl['editor'] = '<textarea id="pgbcontent" name="pgbcontent" cols="45" rows="10" style="width: '.(int)$tmpl['editor_width'].'px; height:'.(int)$tmpl['editor_height'].'px;" class="pgbinput" >'.$formdata->content.'</textarea>';
		
		}
		
		
		// - - - - - - - - - - -
		// Get data - all items
		$items		= $this->get('data');
		$guestbooks	= $this->get('guestbook');
		
		// Define image tag attributes
		if (!empty ($guestbooks->image)) {
			$attribs['align'] = $guestbooks->image_position;
			$attribs['hspace'] = '6';
			// Use the static HTML library to build the image tag
			$tmpl['image'] = JHTML::_('image', 'images/stories/'.$guestbooks->image, JText::_('Phoca Guestbook'), $attribs);
		}
		$pagination	= &$this->get('pagination');
		$tmpl['fwfa']	= explode( ',', trim( $params->get( 'forbidden_word_filter', '' ) ) );
		$tmpl['fwwfa']	= explode( ',', trim( $params->get( 'forbidden_whole_word_filter', '' ) ) );
		


		
		
		
		
		/*$tmpl['formemail'] = 1;
		if ($params->get( 'display_email_form' ) != '')	{$tmpl['formemail'] = $params->get( 'display_email_form' );}
		
		//Add requirement V A L U E S
		$tmpl['title'] = 1;
		if ($params->get( 'require_title' ) != '')		{$tmpl['title'] = $params->get( 'require_title' );}
		
		/*$tmpl['username'] = 1;
		if ($params->get( 'require_username' ) != '')	{$tmpl['username'] = $params->get( 'require_username' );}
		*/
		/*$tmpl['email'] = 0;
		if ($params->get( 'require_email' ) != '')			{$tmpl['email'] = $params->get( 'require_email' );}

		// if we disable email form field and name form field we cannot require these items
		/*if ($tmpl['display_name_form'] == 0) 					{$tmpl['username'] = 0;}
		if ($tmpl['formemail'] == 0) 					{$tmpl['email'] = 0;}*/
		/*
		$tmpl['content'] = 1;
		if ($params->get( 'require_content' ) != '')		{$tmpl['content'] = $params->get( 'require_content' );}
		*/
		$tmpl['registered_users_only'] = $params->get( 'registered_users_only', 0 );
		

		$tmpl['form_position'] 			= $params->get( 'form_position', 0 );
		$tmpl['max_url'] 				= $params->get( 'max_url', 5);
		$tmpl['enable_captcha']	 		= $params->get( 'enable_captcha', 1 );
		$tmpl['enable_captcha_users'] 	= $params->get( 'enable_captcha_users', 0 );
		
		// Captcha not for registered
		if ((int)$tmpl['enable_captcha_users'] == 1) {
			if ((int)$user->id > 0) {
				$tmpl['enable_captcha'] = 0;
			}
		}
		
		//-----------------------------------------------------------------------------------------------
		// !!!! 1. Server Side Checking controll
		//-----------------------------------------------------------------------------------------------
		//Form Variables --------------------------------------------------------------------------------
		//captcha is wrong,we cannot redirect the page,we display message this way
		//DISPLAY MESSAGES WHICH YOU GET FROM CONTROLL FILE - (CONTROLLERS - phocaguestbook.php)

		$smB 				= '<small style="color:#fc0000;">';
		$smE				= '</small><br />';
		$tmpl['errmsg_captcha'] 	= '';
		$tmpl['errmsg_top'] 		= '';
		if (JRequest::getVar( 'captcha-msg', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_captcha'] .= '<tr><td>&nbsp;</td><td colspan="3">'.$smB.JText::_( 'Phoca Guestbook Wrong Captcha' ).'</small></td></tr>';
		}

		if (JRequest::getVar( 'title-msg-1', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook No Title' ).$smE;
		}
		if (JRequest::getVar( 'title-msg-2', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Bad Title' ). $smE;
		}
		if (JRequest::getVar( 'username-msg-1', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook No Username' ). $smE;
		}
		if (JRequest::getVar( 'username-msg-2', 0, 'get', 'int' ) == 1){
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Bad Username' ). $smE;
		}
		if (JRequest::getVar( 'username-msg-3', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Username Exists' ). $smE;
		}
		if (JRequest::getVar( 'email-msg-1', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook No Email' ). $smE;
		}
		if (JRequest::getVar( 'email-msg-2', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Bad Email' ). $smE;
		}
		if (JRequest::getVar( 'email-msg-3', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Email Exists' ). $smE;
		}
		if (JRequest::getVar( 'website-msg-1', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook No Website' ). $smE;
		}
		if (JRequest::getVar( 'website-msg-2', 0, 'get', 'int' ) == 1) {
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Bad Website' ). $smE;
		}
		if (JRequest::getVar( 'content-msg-1', 0, 'get', 'int' ) == 1) {	
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook No Content' ). $smE;
		}
		if (JRequest::getVar( 'content-msg-2', 0, 'get', 'int' ) == 1) {	
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Bad Content' ). $smE;
		}
		if (JRequest::getVar( 'ip-msg-1', 0, 'get', 'int' ) == 1) {	
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook IP Ban' ). $smE;
		}
		if (JRequest::getVar( 'reguser-msg-1', 0, 'get', 'int' ) == 1) {	
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Reg User Only' ). $smE;
		}
		if (JRequest::getVar( 'denyurl-msg-1', 0, 'get', 'int' ) == 1) {	
			$tmpl['errmsg_top'] .= $smB . JText::_( 'Phoca Guestbook Deny URL' ). $smE;
		}
		
	
		
		//Form Variables --------------------------------------------------------------------------------
		
		//-----------------------------------------------------------------------------------------------
		// !!!! 2. Before Server Side Checking controll, don't show form (but there is a server side
		//         checking, it means, if the user hack the form which is not displayed to him
		//         there is a server checking controll too.
		//-----------------------------------------------------------------------------------------------
		//Don't show form, is IP Ban is wrong
	/*	$ip_ban			= trim( $params->get( 'ip_ban', '' ) );
		$ip_ban_array	= explode( ',', $ip_ban );
		
		$i = '192.68.25.23';
		$tmpl['ipa'] 	= 1;//display
		if (is_array($ip_ban_array)) {
			foreach ($ip_ban_array as $value) {
				krumo(trim($value));
				if ($i == trim($value)) {
					$tmpl['ipa'] = 0;
					echo "ano";
					break;// found
				}
			}
		}*/
		$post['ip']		= $_SERVER["REMOTE_ADDR"];
		
		$ip_ban			= trim( $params->get( 'ip_ban' ) );
		$ip_ban_array	= explode( ',', $ip_ban );
		$tmpl['ipa'] 			= 1;//display
		
		if (is_array($ip_ban_array)) {
			foreach ($ip_ban_array as $valueIp) {
				//if ($post['ip'] == trim($value)) {
				if ($valueIp != '') {
					if (strstr($post['ip'], trim($valueIp)) && strpos($post['ip'], trim($valueIp))==0) {
						$tmpl['ipa'] = 0;
						JRequest::setVar( 'ip-msg-1', 	1, 'get',true );
						break;
					}
				}
			}
		}
		
		// Display or not to display the form
		// If user is registered - return 1, if not return 0, if not but the form can be displayed for not registered, return 1
		$tmpl['registered_users_only']	= PhocaguestbookHelper::isRegisteredUser($tmpl['registered_users_only'],$user->id );
		$tmpl['show_form']				= 1;
		
		if ($tmpl['ipa'] == 0) {
			$tmpl['show_form']	= 0;
			$tmpl['ipa_msg'] 	= '<p>' . JText::_('Phoca Guestbook IP Ban No Access') . '</p>';
		} else {
			$tmpl['ipa_msg'] 	= '';
		} 
		
		if ($tmpl['registered_users_only'] == 0){
			$tmpl['show_form']	= 0;
			$tmpl['reguser_msg']= '<p>' . JText::_('Phoca Guestbook Reg User Only No Access'). '</p>';
		} else {
			$tmpl['reguser_msg']='';
		} 
		$tmpl['m']							= PhocaguestbookHelper::getInfo();
		$this->assignRef( 'tmpl' ,			$tmpl);
		$this->assignRef( 'id' ,			$id);		
		$this->assignRef( 'formdata' ,		$formdata);//captcha is wrong, add the same values via POST into form as they were
		$this->assignRef( 'items' ,			$items);
		$this->assignRef( 'guestbooks', 	$guestbooks);
		$this->assignRef( 'params' ,		$params);
		$this->assignRef( 'pagination', 	$pagination);
		$this->assign('action',	$uri->toString());
		parent::display($tpl);
	}
}
?>
