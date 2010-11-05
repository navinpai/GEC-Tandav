<?php
/**
 * @version		$Id: view.html.php 306 2010-01-11 16:09:17Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2ViewItem extends JView
{

	function display($tpl = null) {
	
		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		jimport('joomla.filesystem.file');
		jimport('joomla.html.pane'); 
		JRequest::setVar('hidemainmenu', 1);
		$document = &JFactory::getDocument();
		$document->addScript(JURI::root().'administrator/components/com_k2/lib/Autocompleter.js');
		$document->addScript(JURI::root().'administrator/components/com_k2/lib/observer.js');
		$model = & $this->getModel();
		$item = $model->getData();
		JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'video' );
		$user = & JFactory::getUser();
		if ( JTable::isCheckedOut($user->get ('id'), $item->checked_out )) {
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('The item'), $item->title);
			$mainframe->redirect('index.php?option=com_k2', $msg);
		}
		
		if ($item->id){
			$item->checkout($user->get('id'));
		}
		else {
			$createdate =& JFactory::getDate();
			$item->published = 1;
			$item->publish_up = $createdate->toUnix();
			$item->publish_down = JText::_('Never');
			$item->created = $createdate->toUnix();
			$item->modified = $db->getNullDate();
		}
	
		$item->created = JHTML::_('date', $item->created, '%Y-%m-%d %H:%M:%S');
		$item->publish_up = JHTML::_('date', $item->publish_up, '%Y-%m-%d %H:%M:%S');
		
		if (JHTML::_('date', $item->publish_down, '%Y') <= 1969 || $item->publish_down == $db->getNullDate()) {
			$item->publish_down = JText::_('Never');
		} 
		else {
			$item->publish_down = JHTML::_('date', $item->publish_down, '%Y-%m-%d %H:%M:%S');
		}
		
		$params = & JComponentHelper::getParams('com_k2');
		$wysiwyg = & JFactory::getEditor();
		
		if ($params->get("mergeEditors")){
			
			if (JString::strlen($item->fulltext) > 1) {
				$textValue = $item->introtext."<hr id=\"system-readmore\" />".$item->fulltext;
			} 
			else {
				$textValue = $item->introtext;
			}
			$text = $wysiwyg->display('text', $textValue, '100%', '400', '40', '5', array('pagebreak'));
			$this->assignRef('text', $text);
		}
		
		else {
			$introtext = $wysiwyg->display('introtext', $item->introtext, '100%', '400', '40', '5', array('pagebreak', 'readmore'));
			$this->assignRef('introtext', $introtext);
			$fulltext = $wysiwyg->display('fulltext', $item->fulltext, '100%', '400', '40', '5', array('pagebreak', 'readmore'));
			$this->assignRef('fulltext', $fulltext);
		}

		$lists = array ();
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $item->published);
		$lists['access'] = JHTML::_('list.accesslevel', $item);
		
		$query = "SELECT ordering AS value, title AS text FROM #__k2_items WHERE catid={$item->catid}";
		$lists['ordering'] = JHTML::_('list.specificordering', $item, $item->id, $query);
		
		require_once (JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel = new K2ModelCategories;
		$categories = $categoriesModel->categoriesTree();
		$lists['catid'] = JHTML::_('select.genericlist', $categories, 'catid', 'class="inputbox"', 'value', 'text', $item->catid);
		
		$lists['checkSIG']=$model->checkSIG();
		$lists['checkAllVideos']=$model->checkAllVideos();

		$remoteVideo = false;
		$providerVideo = false;
		$embedVideo = false;

		if (stristr($item->video,'remote}') !== false) {
			$remoteVideo = true;
			$options['startOffset']= 1;
		}

		$providers = $model->getVideoProviders();
		
		if (count($providers)){
			
			foreach ($providers as $provider){
				$providersOptions[] = JHTML::_('select.option', $provider, $provider);
				if (stristr($item->video,"{{$provider}}") !== false) {
					$providerVideo = true;
					$options['startOffset']= 2;
				}
			}	
			
		}
		
		if (JString::substr($item->video, 0, 1) !== '{') {
				$embedVideo = true;
				$options['startOffset']= 3;
		}
		
		$lists['uploadedVideo'] = (!$remoteVideo && !$providerVideo && !$embedVideo)?true:false;
		
		if ($lists['uploadedVideo']){
			$options['startOffset']= 0;
		}
		
		$lists['remoteVideo'] = ($remoteVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $item->video):'';
		$lists['remoteVideoType'] = ($remoteVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $item->video):'';
		$lists['providerVideo'] = ($providerVideo)?preg_replace('%\{[a-z0-9-_]*\}(.*)\{/[a-z0-9-_]*\}%i', '\1', $item->video):'';
		$lists['providerVideoType'] = ($providerVideo)?preg_replace('%\{([a-z0-9-_]*)\}.*\{/[a-z0-9-_]*\}%i', '\1', $item->video):'';
		$lists['embedVideo'] = ($embedVideo)?$item->video:'';
		
		if (isset($providersOptions)){
			$lists['providers'] = JHTML::_('select.genericlist', $providersOptions, 'videoProvider', '', 'value', 'text', $lists['providerVideoType']);
		}
		
		JPluginHelper::importPlugin ('content', 'jw_sigpro');
		JPluginHelper::importPlugin ('content', 'jw_sig');
		JPluginHelper::importPlugin ('content', 'jw_allvideos');
		
		$dispatcher = &JDispatcher::getInstance ();
		
		$params->set('galleries_rootfolder', 'media/k2/galleries');
		$params->set('thb_width', '150');
		$params->set('thb_height', '120');
		$params->set('popup_engine', 'mootools_slimbox');
		$params->set('enabledownload', '0');
		$item->text=$item->gallery;
		$dispatcher->trigger ( 'onPrepareContent', array (&$item, &$params, null ) );
		$item->gallery=$item->text;
		
		if(!$embedVideo){
			$params->set('vfolder', 'media/k2/videos');
			$item->text=$item->video;
			$dispatcher->trigger ( 'onPrepareContent', array (&$item, &$params, null ) );
			$item->video=$item->text;
		}
		else {
			$item->video='<div>'.$item->video.'</div>';
		}
		
		if (isset($item->created_by)) {
			$author= & JUser::getInstance($item->created_by);
			$item->author=$author->name;
		}
		if (isset($item->modified_by)) {
			$moderator = & JUser::getInstance($item->modified_by);
			$item->moderator=$moderator->name;
		}
		
		if($item->id)
			$active = $item->created_by;
		else	
			$active = $user->id;
		
		$lists['authors'] = JHTML::_('list.users', 'created_by', $active, false);
		
		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel= new K2ModelCategories;
		$categories_option[]=JHTML::_('select.option', 0, JText::_('- Select category -'));
		$categories = $categoriesModel->categoriesTree();
		$categories_options=@array_merge($categories_option, $categories);
		$lists['categories'] = JHTML::_('select.genericlist', $categories_options, 'catid', '', 'value', 'text', $item->catid);
		
		JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
		$category = & JTable::getInstance('K2Category', 'Table');
		$category->load($item->catid);
		
		require_once(JPATH_COMPONENT.DS.'models'.DS.'extrafield.php');
		$extraFieldModel= new K2ModelExtraField;
		if($item->id)
			$extraFields = $extraFieldModel->getExtraFieldsByGroup($category->extraFieldsGroup);
		else $extraFields = NULL;
		
		
		for($i=0; $i<sizeof($extraFields); $i++){
			$extraFields[$i]->element=$extraFieldModel->renderExtraField($extraFields[$i],$item->id);
		}
		
		if($item->id)
			$item->attachments=$model->getAttachments($item->id);
		else $item->attachments = NULL;
		
		$tags=$model->getAvailableTags($item->id);
		$lists['tags'] = JHTML::_ ( 'select.genericlist', $tags, 'tags', 'multiple="multiple" size="10" ', 'id', 'name' );
		
		if (isset($item->id)){
			$item->tags=$model->getCurrentTags($item->id);
			$lists['selectedTags'] = JHTML::_ ( 'select.genericlist', $item->tags, 'selectedTags[]', 'multiple="multiple" size="10" ', 'id', 'name' );
		}
		else {
			$lists['selectedTags']='<select size="10" multiple="multiple" id="selectedTags" name="selectedTags[]"></select>';
		}

		$lists['metadata']=new JParameter($item->metadata);
		
		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_L.jpg'))
			$item->image = JURI::root().'media/k2/items/cache/'.md5("Image".$item->id).'_L.jpg';
			
		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_S.jpg'))
			$item->thumb = JURI::root().'media/k2/items/cache/'.md5("Image".$item->id).'_S.jpg';
		
		JPluginHelper::importPlugin ( 'k2' );
		$dispatcher = &JDispatcher::getInstance ();
		
		$K2PluginsItemContent=$dispatcher->trigger('onRenderAdminForm', array (&$item, 'item', 'content' ) );
		$this->assignRef('K2PluginsItemContent', $K2PluginsItemContent);
		
		$K2PluginsItemImage=$dispatcher->trigger('onRenderAdminForm', array (&$item, 'item', 'image' ) );
		$this->assignRef('K2PluginsItemImage', $K2PluginsItemImage);
		
		$K2PluginsItemGallery=$dispatcher->trigger('onRenderAdminForm', array (&$item, 'item', 'gallery' ) );
		$this->assignRef('K2PluginsItemGallery', $K2PluginsItemGallery);
		
		$K2PluginsItemVideo=$dispatcher->trigger('onRenderAdminForm', array (&$item, 'item', 'video' ) );
		$this->assignRef('K2PluginsItemVideo', $K2PluginsItemVideo);
		
		$K2PluginsItemExtraFields=$dispatcher->trigger('onRenderAdminForm', array (&$item, 'item', 'extra-fields' ) );
		$this->assignRef('K2PluginsItemExtraFields', $K2PluginsItemExtraFields);
		
		$K2PluginsItemAttachments=$dispatcher->trigger('onRenderAdminForm', array (&$item, 'item', 'attachments' ) );
		$this->assignRef('K2PluginsItemAttachments', $K2PluginsItemAttachments);
		
		$K2PluginsItemOther=$dispatcher->trigger('onRenderAdminForm', array (&$item, 'item', 'other' ) );
		$this->assignRef('K2PluginsItemOther', $K2PluginsItemOther);
	
		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'item.xml');
		$form->loadINI($item->params);
		$this->assignRef('form', $form);
		
		$this->assignRef('extraFields', $extraFields);
		$this->assignRef('options', $options);
		$this->assignRef('row', $item);
		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		(JRequest::getInt('cid'))? $title = JText::_('Edit Item') : $title = JText::_('Add Item');
		JToolBarHelper::title($title);
		JToolBarHelper::save();
		JToolBarHelper::custom('saveAndNew','save.png','save_f2.png',JText::_('Save &amp; New'), false);
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	
		parent::display($tpl);
	}

}
