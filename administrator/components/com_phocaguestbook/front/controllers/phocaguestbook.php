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
jimport('joomla.mail.helper');

class PhocaGuestbookControllerPhocaGuestbook extends PhocaGuestbookController
{
	function __construct() {
		parent::__construct();
		$this->registerTask('submit', 'submit');
		$this->registerTask('delete', 'remove');
		$this->registerTask('unpublish', 'unpublish');
	}
	
	function display() {
		parent::display();
	}

	function submit() {
		
		global $mainframe;
		$user 	= &JFactory::getUser();
		$db 	= &JFactory::getDBO();
		$uri 	= &JFactory::getURI();
		
		$token	= JUtility::getToken();
		if (!JRequest::getInt( $token, 0, 'post' )) {
			$mainframe->redirect(JRoute::_('index.php', false), JText::_("Form data is not valid"));
			exit;
		}
		
		//Get Session Data (we have saved new session, because we want to check captcha
		$session 					=& JFactory::getSession();
		$phoca_guestbook_session 	= $session->get('phocaguestbooksession');
		
		// - - - - - - - - - - 
		//Some POST data can be required or not, If yes, set message if there is POST data == ''
		//Get the params, e.g. if we define in params, that e.g. title can be "", we will not check it
		//if params doesn't exist it will be required, if exists and is required (1) it is required
		$params	= &$mainframe->getParams();//Add requirement
		
		$tmpl['display_title_form'] 	= $params->get( 'display_title_form', 2 );
		$tmpl['display_name_form'] 		= $params->get( 'display_name_form', 2 );
		$tmpl['display_email_form']	 	= $params->get( 'display_email_form', 1 );
		$tmpl['display_website_form'] 	= $params->get( 'display_website_form', 0 );
		$tmpl['display_content_form'] 	= $params->get( 'display_content_form', 2 );
		$tmpl['max_char'] 				= $params->get( 'max_char', 2000 );
		$tmpl['send_mail'] 				= $params->get( 'send_mail', 0 );
		$tmpl['registered_users_only'] 	= $params->get( 'registered_users_only', 0 );
		$tmpl['enable_captcha']	 		= $params->get( 'enable_captcha', 1 );
		$tmpl['enable_captcha_users']	= $params->get( 'enable_captcha_users', 0 );
		$tmpl['username_or_name'] 		= $params->get( 'username_or_name', 0 );
		$tmpl['predefined_name'] 		= $params->get( 'predefined_name', '' );
		$tmpl['disable_user_check'] 	= $params->get( 'disable_user_check', 0 );
		$tmpl['enable_html_purifier'] 	= $params->get( 'enable_html_purifier', 1 );
		
		//Get POST Data - - - - - - - - - 
		$post				= JRequest::get('post');
		$post['content']	= JRequest::getVar( 'pgbcontent', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		if (!isset($post['captcha'])) {
			$post['captcha'] = 0;
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
		
		$cid				= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['catid'] 		= (int) $cid[0];
		
		$post['published'] 	= (int) 1;
		if ($params->get( 'review_item' ) != '') {
			$post['published'] = (int)$params->get( 'review_item' );
		}
		$post['ip']			= $_SERVER["REMOTE_ADDR"];
		
		
		if (!isset($post['pgusername'])) {
			$post['username']	= '';
		} else {
			$post['username']	= $post['pgusername'];
		}
		
		if (!isset($post['email'])) {
			$post['email']	= '';
		}
		if (!isset($post['website'])) {
			$post['website']	= '';
		}
		
		

		

		// Maximum of character, they will be saved in database
		$post['content']	= substr($post['content'], 0, $tmpl['max_char']);

		// Title Check
		if ($tmpl['display_title_form'] == 2) {
			if ( $post['title'] && trim($post['title']) !='' ) {
				$title = 1;// there is a value in title ... OK
			} else {
				$title = 0;
				JRequest::setVar( 'title-msg-1', 1, 'get',true );// there is no value in title ... FALSE
			}
		} else {
			$title = 1;//there is a value or there is no value but it is not required, so it is OK
		}
		
		if ($title != 0 && eregi( "[\<|\>]", $post['title'])) {
			$title = 0;
			JRequest::setVar( 'title-msg-2', 	1, 'get',true );
		}
		
		// Username or name check
		//$post is the same for both (name or username)
		//$tmpl['username'] is the same for both (name or username)
		if ($tmpl['username_or_name'] == 1) {
			if ($tmpl['display_name_form'] == 2) {
				if ( $post['username'] && trim($post['username']) !='' ) {
					$username = 1;
				} else {
					$username = 0;
					JRequest::setVar( 'username-msg-1', 	1, 'get',true );
				}
			} else {
				$username = 1;
			}
			
			if ($username != 0 && eregi( "[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", $post['username'])) {
				$username = 0;
				JRequest::setVar( 'username-msg-2', 	1, 'get',true );
			}
			
			if ($tmpl['disable_user_check'] == 0) {
				// Check for existing username
				$query = 'SELECT id'
				. ' FROM #__users '
				. ' WHERE username = ' . $db->Quote($post['username'])
				. ' OR name = ' . $db->Quote($post['username'])
				. ' AND id != '. (int) $user->id;
				$db->setQuery( $query );
				$xid = intval( $db->loadResult() );
				if ($xid && $xid != intval( $user->id )) {
					$username = 0;
					JRequest::setVar( 'username-msg-3', 	1, 'get',true );
				}
			}
		} else {
			if ($tmpl['display_name_form'] == 2) {
				if ( $post['username'] && trim($post['username']) !='' ) {
					$username = 1;
				} else {
					$username = 0;
					JRequest::setVar( 'username-msg-1', 	1, 'get',true );
				}
			} else {
				$username = 1;
			}
			
			if ($username != 0 && eregi( "[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+]", $post['username'])) {
				$username = 0;
				JRequest::setVar( 'username-msg-2', 	1, 'get',true );
			}
			
			if ($tmpl['disable_user_check'] == 0) {
				// Check for existing username
				$query = 'SELECT id'
				. ' FROM #__users '
				. ' WHERE username = ' . $db->Quote($post['username'])
				. ' OR name = ' . $db->Quote($post['username'])
				. ' AND id != '. (int) $user->id;
				$db->setQuery( $query );
				$xid = intval( $db->loadResult() );
				if ($xid && $xid != intval( $user->id )) {
					$username = 0; JRequest::setVar( 'username-msg-3', 	1, 'get',true );
				}
			}
		}
		
		// Email Check
		if ($tmpl['display_email_form'] == 2) {
			if ($post['email'] && trim($post['email']) !='' ) {
				$email = 1;
			} else {
				$email = 0;
				JRequest::setVar( 'email-msg-1', 	1, 'get',true );
			}
			
			if ($email != 0 && ! JMailHelper::isEmailAddress($post['email']) ) {
				$email = 0;
				JRequest::setVar( 'email-msg-2', 1, 'get',true );
			}	
		} else {
			$email = 1;
			
			if ($email != 0 && $post['email'] != '' && ! JMailHelper::isEmailAddress($post['email']) ) {
				$email = 0;
				JRequest::setVar( 'email-msg-2', 1, 'get',true );
			}
		}

		if ($tmpl['disable_user_check'] == 0) {
			// check for existing email
			$query = 'SELECT id'
				. ' FROM #__users '
				. ' WHERE email = '. $db->Quote($post['email'])
				. ' AND id != '. (int) $user->id;
			$db->setQuery( $query );
			$xid = intval( $db->loadResult() );
			if ($xid && $xid != intval( $user->id )) {
				$email = 0; JRequest::setVar( 'email-msg-3', 1, 'get',true );
			}
		}
		// Website Check
		if ($tmpl['display_website_form'] == 2) {
			if ($post['website'] && trim($post['website']) !='' ) {
				$website = 1;
			} else {
				$website = 0; JRequest::setVar( 'website-msg-1', 	1, 'get',true );
			}
			
			if ($website != 0 && !PhocaguestbookHelper::isURLAddress($post['website']) ) {
				$website = 0;
				JRequest::setVar( 'website-msg-2', 1, 'get',true );
			}
			
		} else {
			$website = 1;
			if ($website != 0 && $post['website'] != '' && !PhocaguestbookHelper::isURLAddress($post['website']) ) {
				$website = 0;
				JRequest::setVar( 'website-msg-2', 1, 'get',true );
			}
		}
		
		// Content Check
		if ($tmpl['display_content_form'] == 2) {
			if ($post['content'] && trim($post['content']) !='' ) {
				$content = 1;
			} else {
				$content = 0; JRequest::setVar( 'content-msg-1', 	1, 'get',true );
			}
		} else {
			$content = 1;
		}
		
		// IP BAN Check
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
		
		// Not allowed URLs
		$tmpl['deny_url_words'] = $params->get( 'deny_url_words', '' );
		if (!empty($tmpl['deny_url_words'])) {
			$tmpl['deny_url_words'] = explode(',',$params->get( 'deny_url_words', '' ));
		}

		if (!empty($tmpl['deny_url_words']) && $content == 1) {
			$deny_url = 1;
			foreach ($tmpl['deny_url_words'] as $word) {
				if ($word != '') {
					if ((strpos($post['content'], $word) !== false)
					   || (strpos($post['title'], $word) !== false)
					   || (strpos($post['username'], $word) !== false)) {
						$deny_url = 0;
						JRequest::setVar( 'denyurl-msg-1', 	1, 'get',true );
					}
				}
			}
		} else {
			$deny_url = 1;
		}
		
		
		// Registered user Check
		if ($tmpl['registered_users_only'] == 1) {
			if ( $user->id > 0 ) {
				$reguser = 1;
			} else {
				$reguser = 0; JRequest::setVar( 'reguser-msg-1', 	1, 'get',true );
			}
		} else {
			$reguser = 1;
		}
		
		// Captcha not for registered
		if ((int)$tmpl['enable_captcha_users'] == 1) {
			if ((int)$user->id > 0) {
				$tmpl['enable_captcha'] = 0;
			}
		}
		
		// Enable or disable Captcha
		if ($tmpl['enable_captcha'] < 1) {
			$phoca_guestbook_session 	= 1;
			$post['captcha'] 			= 1;
		}
		
		/*
		if ($content != 0 && eregi( "[\<|\>]", $post['content'])) {
			$content = 0; JRequest::setVar( 'content-msg-2', 	1, 'get',true );
		}*/
		
		// SAVING DATA - - - - - - - - - - 
		//the captcha picture code is the same as captcha input code, we can save the data
		//and other post data are OK
		
		
		if ($phoca_guestbook_session && 
			$post['captcha'] && 
			$phoca_guestbook_session == $post['captcha'] && 
			$title == 1 && 
			$username == 1 && 
			$email==1 && 
			$content == 1 &&
			$website == 1 &&
			$tmpl['ipa'] == 1 &&
			$deny_url == 1 &&
			$reguser == 1 && 
			isset($post['task']) && 
			$post['task'] == 'submit' &&
			isset($post['save']) && 
			isset($post['published'])) {
			
			$model = $this->getModel( 'phocaguestbook' );
			
			$post['homesite']	= $post['website'];

			if ($model->store($post)) {
				// Send mail to admin or super admin or user
				
				if ((int)$tmpl['send_mail'] > 0) {
					PhocaGuestbookControllerPhocaGuestbook::sendPhocaGuestbookMail((int)$tmpl['send_mail'], $post, $uri->toString(), $tmpl);
				}
				
				if ($post['published'] == 0) {
					$msg = JText::_( 'Phoca Guestbook Item Saved' ). ", " .JText::_( 'Review Message' );
				} else {
					$msg = JText::_( 'Phoca Guestbook Item Saved' );
				}
			} else {
				$msg = JText::_( 'Error Saving Phoca Guestbook Item' );
			}
			
			// Set Itemid id for redirect, exists this link in Menu?
		/*	$menu 	= &JSite::getMenu();
			$items	= $menu->getItems('link', 'index.php?option=com_phocaguestbook&view=phocaguestbook&id='.(int) $cid[0]);

			if(isset($items[0])) {
				$itemid = $items[0]->id;
				$alias 	= $items[0]->alias;
			}		*/	
			// No JRoute - there are some problems
			// $this->setRedirect(JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbook&id='. (int) $cid[0].'&Itemid='.$itemid),$msg );
			$this->setRedirect($uri->toString(),$msg );

		} else {// captcha image code is not the same as captcha input field (don't redirect because we need post data)
			if ($post['captcha'] == 0)							{JRequest::setVar( 'captcha-msg', 1, 'get',true );}
			if (!$post['captcha'])								{JRequest::setVar( 'captcha-msg', 1, 'get',true );}
			if ($phoca_guestbook_session != $post['captcha'])	{JRequest::setVar( 'captcha-msg', 1, 'get',true );}
			$this->display();
		}		
	}
	
	function remove() {
		global $mainframe;
		$user 		= &JFactory::getUser();
		$cid 		= JRequest::getVar( 'mid', null, '', 'int' );
		$id 		= JRequest::getVar( 'id', null, '', 'int' );
		$itemid 	= JRequest::getVar( 'Itemid', null, '', 'int' );
		$limitstart = JRequest::getVar( 'limitstart', null, '', 'int' );
		$model 		= $this->getModel( 'phocaguestbook' );
	
		if (strtolower($user->usertype) == strtolower('super administrator') || strtolower($user->usertype) == strtolower('administrator')) {

			if (count( $cid ) < 1) {
				JError::raiseError(500, JText::_( 'Select an item to delete' ) );
			}
			if(!$model->delete($cid)) {
				echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
				$msg = JText::_( 'Error Deleting Phoca Guestbook Item' );
			} else {
				$msg = JText::_( 'Phoca Guestbook Item Deleted' );
			}
		} else {
			$msg = JText::_( 'You are not authorized to delete selected item' );
		}
		// Limitstart (if we delete the last item from last pagination, this pagination will be lost, we must change limitstart)
		$countItem = $model->countItem($id);
		if ((int)$countItem[0] == $limitstart) {
			$limitstart = 0;
		}

		// Redirect
		$link	= 'index.php?option=com_phocaguestbook&view=phocaguestbook&id='.$id.'&Itemid='.$itemid.'&limitstart='.$limitstart;
		$link	= JRoute::_($link, false);
		$this->setRedirect( $link, $msg );
	}
	
	function unpublish() {
		global $mainframe;
		$user 		=& JFactory::getUser();
		$cid 		= JRequest::getVar( 'mid', null, '', 'int' );
		$id 		= JRequest::getVar( 'id', null, '', 'int' );
		$itemid 	= JRequest::getVar( 'Itemid', null, '', 'int' );
		$limitstart = JRequest::getVar( 'limitstart', null, '', 'int' );
		$model 		= $this->getModel( 'phocaguestbook' );
		
		if (strtolower($user->usertype) == strtolower('super administrator') || strtolower($user->usertype) == strtolower('administrator')) {
			
			if (count( $cid ) < 1) {
				JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
			}
			if(!$model->publish($cid, 0)) {
				echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
				$msg = JText::_( 'Error Unpublishing Phoca Guestbook Item' );
			}
			else {
				$msg = JText::_( 'Phoca Guestbook Item unpublished' );
			}
		} else {
			$msg = JText::_( 'You are not authorized to unpublish selected item' );
		}
		
		// Limitstart (if we delete the last item from last pagination, this pagination will be lost, we must change limitstart)
		$countItem = $model->countItem($id);

		if ((int)$countItem[0] == $limitstart) {
			$limitstart = 0;
		}
		
		// Redirect
		$link	= 'index.php?option=com_phocaguestbook&view=phocaguestbook&id='.$id.'&Itemid='.$itemid.'&limitstart='.$limitstart;
		$link	= JRoute::_($link, false);
		$this->setRedirect( $link, $msg );
	}
	
	
	function sendPhocaGuestbookMail ($id, $post, $url, $tmpl) {
		global $mainframe;
		$db 		= JFactory::getDBO();
		$sitename 	= $mainframe->getCfg( 'sitename' );
		
		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
		' FROM #__users' .
		' WHERE id = '.(int)$id;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if (isset($post['title']) && $post['title'] != '') {
			$subject = $sitename .' ('.JText::_( 'New Phoca Guestbook Item' ). '): '.PhocaguestbookHelper::wordDelete($post['title'], 25,'...');
			$title = $post['title'];
		} else {
			$subject = $sitename ." (".JText::_( 'New Phoca Guestbook Item' ).')';
			$title = $post['title'];
		}
		
		if (isset($post['username']) && $post['username'] != '') {
			$fromname = $post['username'];
		} else {
			$fromname = $tmpl['predefined_name'];
		}
		
		if (isset($post['email']) && $post['email'] != '') {
			$mailfrom = $post['email'];
		} else {
			$mailfrom = $rows[0]->email;
		}
		
		if (isset($post['content']) && $post['content'] != '') {
			$content = $post['content'];
		} else {
			$content = "...";
		}
		
		$email = $rows[0]->email;
		
		$post['content'] = str_replace("</p>", "\n", $post['content'] );
		$post['content'] = strip_tags($post['content']);
		
		$message = JText::_( 'New Phoca Guestbook item saved' ) . "\n\n"
							. JText::_( 'Website' ) . ': '. $sitename . "\n"
							. JText::_( 'From' ) . ': '. $fromname . "\n"
							. JText::_( 'Date' ) . ': '. JHTML::_('date',  gmdate('Y-m-d H:i:s'), JText::_( 'DATE_FORMAT_LC2' )) . "\n\n"
							. JText::_( 'Title' ) . ': '.$title."\n"
							. JText::_( 'Message' ) . ': '."\n"
							. "\n\n"
							.PhocaguestbookHelper::wordDelete($post['content'],400,'...')."\n\n"
							. "\n\n"
							. JText::_( 'Click the link' ) ."\n"
							. $url."\n\n"
							. JText::_( 'Regards' ) .", \n"
							. $sitename ."\n";
					
		$subject = html_entity_decode($subject, ENT_QUOTES);
		$message = html_entity_decode($message, ENT_QUOTES);
		
		JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);	
		return true;
	}
	
}
?>
