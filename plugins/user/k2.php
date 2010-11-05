<?php
/**
 * @version		$Id: k2.php 328 2010-01-15 18:37:06Z joomlaworks $
 * @package		K2
 * @author    	JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgUserK2 extends JPlugin {

    function plgUserK2(&$subject, $config) {

        parent::__construct($subject, $config);
    }

    function onAfterStoreUser($user, $isnew, $success, $msg) {

        $mainframe = &JFactory::getApplication();
        $params = &JComponentHelper::getParams('com_k2');
        jimport('joomla.filesystem.file');
        
        if ($mainframe->isAdmin()) {
            return;
        }

        $task = JRequest::getCmd('task');
        if ($task == 'activate') {
            return;
        }

        if (!JRequest::getInt('K2UserForm'))
            return;

        JPlugin::loadLanguage('com_k2');
        
        $group = $params->get('K2UserGroup', 1);

        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');
        $row = &JTable::getInstance('K2User', 'Table');
        $k2id = $this->getK2UserID($user['id']);
        JRequest::setVar('id', $k2id, 'post');
        $row->bind(JRequest::get('post'));
        $row->set('userID', $user['id']);
        $row->set('userName', $user['name']);
        $row->set('group', $group);
        $row->set('gender', JRequest::getVar('gender'));
        $row->set('url', JRequest::getVar('url'));
        $row->set('description', JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW));

        $file = JRequest::get('files');

        require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'class.upload.php');
        $savepath = JPATH_ROOT.DS.'media'.DS.'k2'.DS.'users'.DS;

        if (isset($file['image']) && $file['image']['error'] == 0 && !JRequest::getBool('del_image')) {
            $handle = new Upload($file['image']);
            $handle->allowed = array('image/*');
            if ($handle->uploaded) {
                $handle->file_auto_rename = false;
                $handle->file_overwrite = true;
                $handle->file_new_name_body = $row->id;
                $handle->image_resize = true;
                $handle->image_ratio_y = true;
                $handle->image_x = $params->get('userImageWidth', '100');
                $handle->Process($savepath);
                $handle->Clean();
            } else {
                $mainframe->enqueueMessage(JText::_('Could not upload your image.').$handle->error, 'notice');
            }
            $row->image = $handle->file_dst_name;
        }

        if (JRequest::getBool('del_image')) {

            if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'k2'.DS.'users'.DS.$row->image)) {
                JFile::delete(JPATH_ROOT.DS.'media'.DS.'k2'.DS.'users'.DS.$row->image);
            }
            $row->image = '';
        }

        $row->store();
		$itemid = $params->get('redirect');

		if(!$isnew && $itemid){
			$menu =& JSite::getMenu();
			$item = $menu->getItem($itemid);
			$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
			if (JURI::isInternal($url)) {
				$mainframe->redirect( $url, JText::_( 'Your settings have been saved.' ) );
			}
		}


    }

    function onAfterDeleteUser($user, $succes, $msg) {

        $mainframe = &JFactory::getApplication();
        $db = &JFactory::getDBO();
        $query = "DELETE FROM #__k2_users WHERE userID={$user['id']} LIMIT 1";
        $db->setQuery($query);
        $db->query();
    }

    function getK2UserID($id) {

        $db = &JFactory::getDBO();
        $query = "SELECT id FROM #__k2_users WHERE userID={$id}";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

}
