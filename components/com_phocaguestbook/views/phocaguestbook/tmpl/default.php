<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.utilities.date');
jimport('joomla.html.pane');
?><script language="javascript" type="text/javascript">
<!--
function submitbutton() {
	
	var novaluesPGB='';
	var formPGB = document.saveForm;
	var textPGB = tinyMCE.get('pgbcontent').getContent();

	
	if (novaluesPGB!=''){}
	<?php
	if ($this->tmpl['display_title_form']== 2) {?>
	else if ( formPGB.title.value == "" ) {
		alert("<?php echo JText::_( 'Phoca Guestbook No Title', true); ?>");return false;} <?php }
	if ($this->tmpl['display_name_form']== 2){?>
	else if( formPGB.pgusername.value == "" ) {
		alert("<?php echo JText::_( 'Phoca Guestbook No Username', true); ?>");return false;}<?php }
	if ($this->tmpl['display_email_form']== 2){?>
	else if( formPGB.email.value == "" ) {
		alert("<?php echo JText::_( 'Phoca Guestbook No Email', true); ?>");return false;}<?php }
	if ($this->tmpl['display_website_form']== 2){?>
	else if( formPGB.website.value == "" ) {
		alert("<?php echo JText::_( 'Phoca Guestbook No Website', true); ?>");return false;}<?php }
	if ($this->tmpl['display_content_form']== 2){?>
	else if( textPGB == "" ) {
		alert("<?php echo JText::_( 'Phoca Guestbook No Content', true); ?>");return false;}<?php } ?>
}
--></script><?php

// - - - - - - - - - - -
// Header
// - - - - - - - - - - -
if ( $this->params->get( 'show_page_title' ) ) { 
	echo '<div class="componentheading'.$this->params->get( 'pageclass_sfx' ).'">'
	. $this->params->get( 'page_title' ) . '</div>';
}
echo '<div class="contentpane'.$this->params->get( 'pageclass_sfx' ).'">';
if ( @$this->image || @$this->guestbooks->description ) {
	echo '<div class="contentdescription'.$this->params->get( 'pageclass_sfx' ).'">';
	if ( isset($this->tmpl['image']) ) {
		echo $this->tmpl['image'];
	}
	echo $this->guestbooks->description;
	echo '</div>';
} 
echo '</div>';
echo '<div id="phocaguestbook">';


// - - - - - - - - - - -
// Form2 - Pagination
// - - - - - - - - - - -
$form2 = '<p>&nbsp;</p><div><form action="'.$this->action.'" method="post" name="adminForm" id="pgbadminForm">';
if (count($this->items)) {
	$form2 .='<center>';
	if ($this->params->get('show_pagination_limit')) {
		$form2 .= '<span style="margin:0 10px 0 10px">'.JText::_('Display Num') .'&nbsp;'.$this->pagination->getLimitBox().'</span>';
	}
	if ($this->params->get('show_pagination')) {
		$form2 .= '<span style="margin:0 10px 0 10px" class="sectiontablefooter'.$this->params->get( 'pageclass_sfx' ).'" >'.$this->pagination->getPagesLinks().'</span><span style="margin:0 10px 0 10px" class="pagecounter">'.$this->pagination->getPagesCounter().'</span>';
	}
	$form2 .='</center>';
}
$form2 .= '</form></div>'.$this->tmpl['m'];

// - - - - - - - - - - -
// Messages - create and correct Messages (Posts, Items)
// - - - - - - - - - - -
$gbPosts = '';//Messages (Posts, Items)
foreach ($this->items as $key => $values) {
	//Maximum of links in the message
	$rand 				= '%phoca' . mt_rand(0,1000) * time() . 'phoca%';
	$ahref_replace 		= "<a ".$rand."=";
	$ahref_search		= "/<a ".$rand."=/";
	$values->content	= preg_replace ("/<a href=/", $ahref_replace, $values->content, $this->tmpl['max_url']);
	$values->content	= preg_replace ("/<a href=.*?>(.*?)<\/a>/",	"$1", $values->content);
	$values->content	= preg_replace ($ahref_search, "<a href=", $values->content);
	
	
	// Forbidden Word Filter
	// Believe or not - it is more faster to replace items than the whole content :-)
	foreach ($this->tmpl['fwfa'] as $key2 => $values2) {
		if (function_exists('str_ireplace')) {
			$values->username 	= str_ireplace (trim($values2), '***', $values->username);
			$values->title		= str_ireplace (trim($values2), '***', $values->title);
			$values->content	= str_ireplace (trim($values2), '***', $values->content);
			$values->email		= str_ireplace (trim($values2), '***', $values->email);
		} else {		
			$values->username 	= str_replace (trim($values2), '***', $values->username);
			$values->title		= str_replace (trim($values2), '***', $values->title);
			$values->content	= str_replace (trim($values2), '***', $values->content);
			$values->email		= str_replace (trim($values2), '***', $values->email);
		}
	}
	
	//Forbidden Whole Word Filter
	foreach ($this->tmpl['fwwfa'] as $key3 => $values3) {
		if ($values3 !='') {
			//$values3			= "/([\. ])".$values3."([\. ])/";
			$values3			= "/(^|[^a-zA-Z0-9_]){1}(".preg_quote(($values3),"/").")($|[^a-zA-Z0-9_]){1}/i";
			$values->username 	= preg_replace ($values3, "\\1***\\3", $values->username);// \\2
			$values->title		= preg_replace ($values3, "\\1***\\3", $values->title);
			$values->content	= preg_replace ($values3, "\\1***\\3", $values->content);
			$values->email		= preg_replace ($values3, "\\1***\\3", $values->email);
		}
	}
	
	//Hack, because Joomla add some bad code to src and href
	if (function_exists('str_ireplace')) {
		$values->content	= str_ireplace ('../plugins/editors/tinymce/', 'plugins/editors/tinymce/', $values->content);
	} else {		
		$values->content	= str_replace ('../plugins/editors/tinymce/', 'plugins/editors/tinymce/', $values->content);
	}
		
	$gbPosts .= '<div class="pgbox" style="border:1px solid '.$this->tmpl['border_color'].';color:'.$this->tmpl['font_color'].';">';
	$gbPosts .= '<h4 class="pgtitle" style="background:'.$this->tmpl['background_color'].';color:'.$this->tmpl['font_color'].';">';
	
	//!!! username saved in database can be username or name
	$sep = 0;
	if ($this->tmpl['display_name'] != 0) {
		if ($values->username != '') {
		$gbPosts .= ' To ';
			$gbPosts .= PhocaguestbookHelper::wordDelete($values->username, 40, '...');
			$sep = 1;
		}
	}
	
	if ($this->tmpl['display_email'] != 0) {
		if ($values->email != '') {
			if ($sep == 1) {
				$gbPosts .= ' ';
				$gbPosts .= '( '. JHTML::_( 'email.cloak', PhocaguestbookHelper::wordDelete($values->email, 50, '...') ).' )';
				$sep = 1;
			} else {
				$gbPosts .= JHTML::_( 'email.cloak', PhocaguestbookHelper::wordDelete($values->email, 50, '...') );
				$sep = 1;
			}
		}
	}
	
	if ($values->title != '') {
		if ($sep == 1) {
			$gbPosts .= ' From ';
		}
		$gbPosts .= PhocaguestbookHelper::wordDelete($values->title, 100, '...');
	}
	
	
	
	if ($this->tmpl['display_website'] != 0) {
		if ($values->homesite != '') {
			
			
			if ($values->title == '' && $values->email == '' && $values->username == '') {
				$gbPosts .= '';
			} else {
				$gbPosts .= ' <br />';
			}
			
			$gbPosts .= ' <span><a href="'.$values->homesite.'">'.PhocaguestbookHelper::wordDelete($values->homesite, 50, '...').'</a></span>';
		}
	}
	
	$gbPosts .= '</h4>';
	
	// SECURITY
	// Open a tag protection
	$a_count 		= substr_count(strtolower($values->content), "<a");
	$a_end_count 	= substr_count(strtolower($values->content), "</a>");
	$quote_count	= substr_count(strtolower($values->content), "\"");
	
	if ($quote_count%2!=0) {
		$end_quote = "\""; // close the " if it is open
	} else {
		$end_quote = "";
	}
	
	if ($a_count > $a_end_count) {
		$end_a = "></a>"; // close the a tag if there is a open a tag
						  // in case <a href> ... <a href ></a>
						  // in case <a ... <a href >></a>
	} else {
		$end_a = "";
	}
	
	$gbPosts .= '<div class="pgcontent" style="overflow: auto;border-left:0px solid '.$this->tmpl['background_color'].';">' . $values->content . $end_quote .$end_a. '</div>';
	$gbPosts .= '<p class="pgcontentbottom"><small style="color:'.$this->tmpl['second_font_color'].'">' . JHTML::_('date',  $values->date, JText::_( $this->tmpl['date_format'] ) ) . '</small>';
	
	if ($this->tmpl['administrator'] != 0) {
		$gbPosts.='<a href="'.JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbook&id='.$this->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int').'&controller=phocaguestbook&task=delete&mid='.$values->id.'&limitstart='.$this->pagination->limitstart).'" onclick="return confirm(\''.JText::_( 'Delete Message' ).'\');">'.JHTML::_('image', 'components/com_phocaguestbook/assets/images/icon-trash.gif', JText::_( 'Delete' )).'</a>';
		
		$gbPosts.='<a href="'.JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbook&id='.$this->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int').'&controller=phocaguestbook&task=unpublish&mid='.$values->id.'&limitstart='.$this->pagination->limitstart).'">'.JHTML::_('image', 'components/com_phocaguestbook/assets/images/icon-unpublish.gif', JText::_( 'Unpublish' )).'</a>';
	}
	$gbPosts.='</p></div>';	
}


// - - - - - - - - - - -
// Form Top (Form 1 - Messages)
// - - - - - - - - - - - 
// Display Messages (Posts, Items)
// Forms (If position = 1 --> Form is bottom, Messages top, if position = 0 --> Form is top, Messages bottom
if ($this->tmpl['form_position'] == 1) {
	echo $gbPosts;
}

if ($this->tmpl['show_form'] == 1) {

	// Display Pane or not
	if ($this->tmpl['display_form'] == 0 ) {
		$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));
		echo $pane->startPane("phocaguestbook-pane");
		
		// if user posted a message and get some error warning (captcha, ...) the form should be open	
		if ($this->tmpl['errmsg_captcha'] == '' && $this->tmpl['errmsg_top'] == '') {
			echo '<div style="display:none">';//because of IE
			echo $pane->startPanel( '', 'phocaguestbook-jpane-none' );
			echo $pane->endPanel();
			echo '</div>';
		}
		echo $pane->startPanel( JText::_('Post message'), 'phocaguestbook-jpane-toggler-down' );
	}

	echo '<div>'
	.'<form action="'.$this->action.'" method="post" name="saveForm" id="pgbSaveForm" onsubmit="return submitbutton();">'
	.'<table width="'.$this->tmpl['table_width'].'">';
	
	if ($this->tmpl['errmsg_top'] != '') {
		echo '<tr>'
		.'<td>&nbsp;</td>'
		.'<td colspan="3">';
		//-- Server side checking 
		echo $this->tmpl['errmsg_top'];
		//-- Server side checking
		echo '&nbsp;</td>'
		.'</tr>';
	}
	
	if ((int)$this->tmpl['display_title_form'] > 0){	
		echo '<tr>'
		.'<td width="5"><strong>'.JText::_('Title').': </strong></td>'
		.'<td colspan="3">'
		.'<input type="text" name="title" id="pgbtitle" value="'. $this->formdata->title .'" size="32" maxlength="200" class="pgbinput" /></td>'
		.'</tr>';
	}
		
	if ((int)$this->tmpl['display_name_form'] > 0){
		echo '<tr>'
		.'<td><strong>'.JText::_('Name').': </strong></td>'
		.'<td colspan="3">'
		.'<input type="text" name="pgusername" id="pgbusername" value="'.$this->formdata->username .'" size="32" maxlength="100" class="pgbinput" /></td>'
		.'</tr>';
	}
		
	if ((int)$this->tmpl['display_email_form'] > 0){
		echo '<tr>'
		.'<td><strong>'.JText::_('Email').': </strong></td>'
		.'<td colspan="3">'
		.'<input type="text" name="email" id="pgbemail" value="'.$this->formdata->email .'" size="32" maxlength="100" class="pgbinput" /></td>'
		.'</tr>';
	}
	
	if ((int)$this->tmpl['display_website_form'] > 0){
		echo '<tr>'
		.'<td><strong>'.JText::_('Website').': </strong></td>'
		.'<td colspan="3">'
		.'<input type="text" name="website" id="pgbwebsite" value="'.$this->formdata->website .'" size="32" maxlength="100" class="pgbinput" /></td>'
		.'</tr>';
	}
	
	if ((int)$this->tmpl['display_content_form'] > 0){
		echo '<tr>'
		.'<td><strong>'.JText::_('Content').': </strong></td>'
		.'<td colspan="3">'.$this->tmpl['editor'].'</td>'
		.'</tr>';
	}
		
	if ((int)$this->tmpl['enable_captcha'] > 0) {
	
		// Server side checking CAPTCHA 
		echo $this->tmpl['errmsg_captcha'];
		//-- Server side checking CAPTCHA
			
		// Set fix height because of pane slider
		$imageHeight = 'style="height:105px"';
		
		echo '<tr>'
		.'<td width="5"><strong>'. JText::_('Image Verification').': </strong></td>'		
		.'<td width="5" align="left" valign="middle" '.$imageHeight . '>';
		
		if ($this->tmpl['captcha_method'] == 0) {
			echo '<img src="'. JRoute::_('index.php?option=com_phocaguestbook&view=phocaguestbooki&id='.$this->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int').'&phocasid='. md5(uniqid(time()))).'" alt="'.JText::_('Captcha Image').'" id="phocacaptcha" />';
		} else {
			echo JHTML::_( 'image.site','index.php?option=com_phocaguestbook&amp;view=phocaguestbooki&amp;id='.$this->id.'&amp;Itemid='.JRequest::getVar('Itemid', 0, '', 'int').'&amp;phocasid='. md5(uniqid(time())), '', '','',JText::_('Captcha Image'), array('id' => 'phocacaptcha'));
		}
		echo '</td>';
				
		echo '<td width="5" align="left" valign="middle">'
		.'<input type="text" id="pgbcaptcha" name="captcha" size="6" maxlength="6" class="pgbinput" /></td>';
				
		echo '<td align="center" width="50" valign="middle">';
		//Remove because of IE6 - href="javascript:void(0)" onclick="javascript:reloadCaptcha();"
		echo '<a href="javascript:reloadCaptcha();" title="'. JText::_('Reload Image').'" >'
		. JHTML::_( 'image.site', 'components/com_phocaguestbook/assets/images/icon-reload.gif', '', '','',JText::_('Reload Image'))
		.'</a></td>';

		echo '</tr>';
	}
		
	echo '<tr>'
	.'<td>&nbsp;</td>'
	.'<td colspan="3">'
	.'<input type="submit" name="save" value="'. JText::_('Submit').'" />'
	.' &nbsp;'
	.'<input type="reset" name="reset" value="'. JText::_('Reset').'" /></td>'
	.'</tr>'
	.'</table>';

	echo '<input type="hidden" name="cid" value="'. $this->id .'" />' . "\n"
	.'<input type="hidden" name="option" value="com_phocaguestbook" />' . "\n"
	.'<input type="hidden" name="view" value="phocaguestbook" />' . "\n"
	.'<input type="hidden" name="controller" value="phocaguestbook" />' . "\n"
	.'<input type="hidden" name="task" value="submit" />' . "\n"
	.'<input type="hidden" name="'. JUtility::getToken().'" value="1" />' . "\n"
	.'</form>'. "\n"
	.'</div><div style="clear:both;">&nbsp;</div>';
	
	// Display Pane or not
	if ($this->tmpl['display_form'] == 0 ) {
		echo $pane->endPanel();
		echo $pane->endPane();
	}
	
} else {
	// Display or not to display Form, Registered user only, IP Ban
	// Show messages (Only registered user, IP Ban)
	echo $this->tmpl['ipa_msg'];
	echo $this->tmpl['reguser_msg'];
}

// - - - - - - - - - - -
// Form Bottom (Form 1 - Messages)
// - - - - - - - - - - - 
//Forms (If position = 1 --> Form is bottom, Messages top, if position = 0 --> Form is top, Messages bottom
if ($this->tmpl['form_position'] == 0) {
	echo $gbPosts;
}
echo $form2;
echo '</div>';