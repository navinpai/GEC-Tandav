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

jimport('joomla.application.component.model');

class PhocaGuestbookModelPhocaGuestbooki extends JModel
{
	var $_image_data = null;
	
	function &getData()
	{
		global $mainframe;
		$params	= &$mainframe->getParams();
		
		$enable_captcha = 1;
		if ($params->get( 'enable_captcha' ) != '') {
			$enable_captcha = $params->get( 'enable_captcha' );
		}
		
		//krumo($params);
		//echo $enable_captcha;exit;
		
		switch ((int)$enable_captcha)
		{
			case 10:
				$random = mt_rand(1,3);
				switch ((int)$random)
				{
					case 3:
						$this->_image_data = PhocaguestbookHelperCaptchaTTF::createImageData();
					break;
					case 2:
						$this->_image_data = PhocaguestbookHelperCaptchaMath::createImageData();
					break;
					case 1:
					default:
						$this->_image_data = PhocaguestbookHelperCaptcha::createImageData();
					break;
				}
			break;
			
			case 11:
				$random = mt_rand(1,2);
				switch ((int)$random)
				{
					case 2:
						$this->_image_data = PhocaguestbookHelperCaptchaMath::createImageData();
					break;
					case 1:
					default:
						$this->_image_data = PhocaguestbookHelperCaptcha::createImageData();
					break;
				}
			break;
			
			case 12:
				$random = mt_rand(1,2);
				switch ((int)$random)
				{
					case 2:
						$this->_image_data = PhocaguestbookHelperCaptchaTTF::createImageData();
					break;
					case 1:
					default:
						$this->_image_data = PhocaguestbookHelperCaptcha::createImageData();
					break;
				}
			break;
			
			case 13:
				$random = mt_rand(1,2);
				switch ((int)$random)
				{
					case 2:
						$this->_image_data = PhocaguestbookHelperCaptchaTTF::createImageData();
					break;
					case 1:
					default:
						$this->_image_data = PhocaguestbookHelperCaptchaMath::createImageData();
					break;
				}
			break;
			
			case 3:
				$this->_image_data = PhocaguestbookHelperCaptchaTTF::createImageData();
			break;
			
			case 2:
				$this->_image_data = PhocaguestbookHelperCaptchaMath::createImageData();
			break;
			
			case 1:
			default:
				$this->_image_data = PhocaguestbookHelperCaptcha::createImageData();
			break;
		}
		return $this->_image_data;
	}
}
?>
