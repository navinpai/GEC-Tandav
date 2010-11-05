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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class PhocaGuestbookCpControllerPhocaGuestbookinstall extends PhocaGuestbookCpController
{
	function __construct() {
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'install'  , 'install' );
		$this->registerTask( 'upgrade'  , 'upgrade' );		
	}

	
	
	function install()
	{		
		
		$db			= &JFactory::getDBO();
		$dbPref 	= $db->getPrefix();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		
		
		$query =' DROP TABLE IF EXISTS `'.$dbPref.'phocaguestbook_items`;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query ='CREATE TABLE `'.$dbPref.'phocaguestbook_items` ('."\n";
		$query.='  `id` int(11) unsigned NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `sid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `username` varchar(100) NOT NULL default \'\','."\n";
		$query.='  `userid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `email` varchar(50) NOT NULL default \'\','."\n";
		$query.='  `homesite` varchar(50) NOT NULL default \'\','."\n";
		$query.='  `ip` varchar(20) NOT NULL default \'\','."\n";
		$query.='  `title` varchar(200) NOT NULL default \'\','."\n";
		$query.='  `content` text NOT NULL default \'\','."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `published` (`published`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query=' DROP TABLE IF EXISTS `'.$dbPref.'phocaguestbook_books`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE `'.$dbPref.'phocaguestbook_books` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `parent_id` int(11) NOT NULL default 0,'."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `name` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `section` varchar(50) NOT NULL default \'\','."\n";
		$query.='  `image_position` varchar(30) NOT NULL default \'\','."\n";
		$query.='  `description` text NOT NULL,'."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `editor` varchar(50) default NULL,'."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `access` tinyint(3) unsigned NOT NULL default \'0\','."\n";
		$query.='  `count` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `cat_idx` (`section`,`published`,`access`),'."\n";
		$query.='  KEY `idx_access` (`access`),'."\n";
		$query.='  KEY `idx_checkout` (`checked_out`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Guestbook not successfully installed' ) . ': <br />' . $msg_sql;
		} else {
			$msg = JText::_( 'Phoca Guestbook successfully installed' );
		}
		
		$link = 'index.php?option=com_phocaguestbook';
		$this->setRedirect($link, $msg);
	}
	
	function upgrade()
	{
		$db			= &JFactory::getDBO();
		$dbPref 	= $db->getPrefix();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		
		$query ='CREATE TABLE IF NOT EXISTS `'.$dbPref.'phocaguestbook_items` ('."\n";
		$query.='  `id` int(11) unsigned NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `sid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `username` varchar(100) NOT NULL default \'\','."\n";
		$query.='  `userid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `email` varchar(50) NOT NULL default \'\','."\n";
		$query.='  `homesite` varchar(50) NOT NULL default \'\','."\n";
		$query.='  `ip` varchar(20) NOT NULL default \'\','."\n";
		$query.='  `title` varchar(200) NOT NULL default \'\','."\n";
		$query.='  `content` text NOT NULL default \'\','."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `published` (`published`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query ='CREATE TABLE IF NOT EXISTS `'.$dbPref.'phocaguestbook_books` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `parent_id` int(11) NOT NULL default 0,'."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `name` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `section` varchar(50) NOT NULL default \'\','."\n";
		$query.='  `image_position` varchar(30) NOT NULL default \'\','."\n";
		$query.='  `description` text NOT NULL,'."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `editor` varchar(50) default NULL,'."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `access` tinyint(3) unsigned NOT NULL default \'0\','."\n";
		$query.='  `count` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `cat_idx` (`section`,`published`,`access`),'."\n";
		$query.='  KEY `idx_access` (`access`),'."\n";
		$query.='  KEY `idx_checkout` (`checked_out`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Guestbook not successfully upgraded' ) . ': <br />' . $msg_sql;
		} else {
			$msg = JText::_( 'Phoca Guestbook successfully upgraded' );
		}
	
		$link = 'index.php?option=com_phocaguestbook';
		$this->setRedirect($link, $msg);
	}
}