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

class PhocaguestbookHelperCaptchaTTF
{
	function createImageData()
	{
		$rand_char 			= PhocaguestbookHelperCaptchaTTF::generateRandomChar(6);
		$image_name 		= PhocaguestbookHelperCaptchaTTF::getRandomImage();
		$image 				= @imagecreatefromjpeg($image_name);
		
		$ttf[0]				= JPATH_COMPONENT . DS . 'assets'. DS . 'captcha'.DS. 'fonts'.DS. 'essai.ttf';
	//	$ttf[1]				= JPATH_COMPONENT . DS . 'assets'. DS . 'captcha'.DS. 'fonts'.DS. 'vera.ttf';
		$ttf[1]				= JPATH_COMPONENT . DS . 'assets'. DS . 'captcha'.DS. 'fonts'.DS. 'justus.ttf';
		
		$i = 15;
		$char_string = '';
		foreach ($rand_char as $key => $value)
		{
			$font_color 	= PhocaguestbookHelperCaptchaTTF::getRandomFontColor();
			$position_x 	= PhocaguestbookHelperCaptchaTTF::getRandomPositionX($i);
			$position_y 	= mt_rand(55,80);
			$font_size 		= mt_rand(20,40);
			$angle			= mt_rand(-30,30);
			$rand_ttf		= mt_rand(0,1);
			
			imagettftext($image, $font_size, $angle, $position_x, $position_y, ImageColorAllocate ($image, $font_color[0], $font_color[1], $font_color[2]), $ttf[$rand_ttf], $value);
			$i = $i + 37;
			$char_string .= $value;
		}

		$image_data['outcome'] 		= $char_string;//$rand_char;
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6)
	{	
	
		global $mainframe;
		$params	= &$mainframe->getParams();
		
		$charGroup = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
		if ($params->get( 'ttf_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $params->get( 'ttf_captcha_chars' ) ));
		}
		$charGroup = explode( ',', $charGroup );
	
		
		srand ((double) microtime() * 1000000);
		
		$random_array = array();
		
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			
			$random_array[]	= $charGroup[$random_char_group];
		}
		return $random_array;
	}

	function getRandomImage() {
		$rand 	= mt_rand(5,8);
		$image 	= '0'.$rand.'.jpg';
		$image 	= JPATH_ROOT.DS.'components'.DS.'com_phocaguestbook'.DS.'assets'.DS.'captcha'.DS . $image;
		return $image;
	}

	function getRandomPositionX($i) {
		$rand_2 = mt_rand(-2,3);
		$rand_3 = $i + ($rand_2);
		$rand 	= mt_rand($i,$rand_3);
		return $rand;
	}

	function getRandomFontColor() {
		$rand = mt_rand(1,6);
		if ($rand == 1) {$font_color[0] = 0; $font_color[1] = 20; $font_color[2] = 0;}
		if ($rand == 2) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 143;}
		if ($rand == 3) {$font_color[0] = 10; $font_color[1] = 102; $font_color[2] = 0;}
		if ($rand == 4) {$font_color[0] = 110; $font_color[1] = 58; $font_color[2] = 0;}
		if ($rand == 5) {$font_color[0] = 170; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 6) {$font_color[0] = 0; $font_color[1] = 93; $font_color[2] = 174;}
		return $font_color;
	}
}


// ================================================


class PhocaguestbookHelperCaptchaMath
{	
	function createImageItem($item)
	{
		switch ($item)
		{
			// 1 ---------------------------------------------------------------------
			case 1:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(10);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"   ".$ch[0]." ",
"  ".$ch[1].$ch[2]." ",
" ".$ch[3]." ".$ch[4]." ",
$ch[5]."  ".$ch[6]." ",
"   ".$ch[7]." ",
"   ".$ch[8]." ",
"   ".$ch[9]." ",
);
			break;
			
			// 2 ---------------------------------------------------------------------
			case 2:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(14);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
"   ".$ch[5]." ",
"  ".$ch[6]."  ",
" ".$ch[7]."   ",
$ch[8]."    ",
$ch[9].$ch[10].$ch[11].$ch[12].$ch[13]
);
			break;
			
			// 3 ---------------------------------------------------------------------
			case 3:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(14);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (		
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
"    ".$ch[5],
"  ".$ch[6].$ch[7]." ",
"    ".$ch[8],
$ch[9]."   ".$ch[10],
" ".$ch[11].$ch[12].$ch[13]." "
);
			break;
			
			// 4 ---------------------------------------------------------------------
			case 4:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(12);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (		
"   ".$ch[0]." ",
"  ".$ch[1]."  ",
" ".$ch[2]."   ",
$ch[3]."  ".$ch[4]." ",
$ch[5].$ch[6].$ch[7].$ch[8].$ch[9],
"   ".$ch[10]." ",
"   ".$ch[11]." "
);
			break;
			
			// 5 ---------------------------------------------------------------------
			case 5:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(17);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
$ch[5]."    ",
$ch[6].$ch[7].$ch[8].$ch[9]." ",
"    ".$ch[10],
"    ".$ch[11],
$ch[12]."   ".$ch[13],
" ".$ch[14].$ch[15].$ch[16]." "
);
			break;
			
			// 6 ---------------------------------------------------------------------
			case 6:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(16);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."    ",
$ch[4]."    ",
$ch[5].$ch[6].$ch[7].$ch[8]." ",
$ch[9]."   ".$ch[10],
$ch[11]."   ".$ch[12],
" ".$ch[13].$ch[14].$ch[15]." "
);
			break;
			
			// 7 ---------------------------------------------------------------------
			case 7:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(11);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"    ".$ch[5],
"    ".$ch[6],
"   ".$ch[7]." ",
"  ".$ch[8]."  ",
" ".$ch[9]."   ",
$ch[10]."    "
);
			break;
			
			// 8 ---------------------------------------------------------------------
			case 8:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(17);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
$ch[5]."   ".$ch[6],
" ".$ch[7].$ch[8].$ch[9]." ",
$ch[10]."   ".$ch[11],
$ch[12]."   ".$ch[13],
" ".$ch[14].$ch[15].$ch[16]." "
);
			break;
			
			// 9 ---------------------------------------------------------------------
			case 9:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(16);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
$ch[5]."   ".$ch[6],
" ".$ch[7].$ch[8].$ch[9].$ch[10],
"    ".$ch[11],
"    ".$ch[12],
" ".$ch[13].$ch[14].$ch[15]." "
);
			break;
			
			// 10 + ------------------------------------------------------------------
			case 10:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(9);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"     ",
"  ".$ch[0]."  ",
"  ".$ch[1]."  ",
$ch[2].$ch[3].$ch[4].$ch[5].$ch[6],
"  ".$ch[7]."  ",
"  ".$ch[8]."  ",
"     "
);
			break;
			
			// 11 - ------------------------------------------------------------------
			case 11:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(5);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"     ",
"     ",
"     ",
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"     ",
"     ",
"     "
);
			break;
			
			// 12 x ------------------------------------------------------------------
			case 12:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(9);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
"     ",			
$ch[0]."   ".$ch[1],
" ".$ch[2]." ".$ch[3]." ",
"  ".$ch[4]."  ",
" ".$ch[5]." ".$ch[6]." ",
$ch[7]."   ".$ch[8],
"     "
);	
			break;
			
			// 13 : ------------------------------------------------------------------
			case 13:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(8);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
"     ",			
"  ".$ch[0].$ch[1]." ",
"  ".$ch[2].$ch[3]." ",
"     ",
"     ",
"  ".$ch[4].$ch[5]." ",
"  ".$ch[6].$ch[7]." "
);	
			break;
			
			// 15 = ------------------------------------------------------------------
			case 15:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(10);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
"     ",
"     ",
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"     ",
$ch[5].$ch[6].$ch[7].$ch[8].$ch[9],
"     ",
"     "
);
			break;
		}
		return $randChar;
	}


	function createImageData()
	{
		$image_name 		= PhocaguestbookHelperCaptchaMath::getRandomImage();
		$image 				= @imagecreatefromjpeg($image_name);
			
		$math = PhocaguestbookHelperCaptchaMath::getMath();

		$items = array( 0 => $math['first'], 1 => $math['operation'], 2 => $math['second'], 3 => 15);
		
		$x = 18;//Position X (first)
		for ($i=0;$i<4;$i++)
		{		
			$randChar = PhocaguestbookHelperCaptchaMath::createImageItem($items[$i]);
			// Position Y (first) ---
			if ($i == 1) {
				$y = 20;
			} else {
				$y = 10;
			}
			// -----------------------
			foreach ($randChar['array'] as $key => $value)
			{
				$font_color 	= PhocaguestbookHelperCaptchaMath::getRandomFontColor();
				
				if ($i == 1 || $i == 3) {
					$font_size 	= 2;
				} else {
					$font_size	= 5;
				}
				
				$position_x 	= $x;
				$position_y		= $y;
				
				ImageString($image, $font_size, $position_x, $position_y, $value, ImageColorAllocate ($image, $font_color[0], $font_color[1], $font_color[2]));
				if ($i == 1) {
					$y = $y + 7;
				} else {
					$y = $y + 11;
				}
			}
			if ($i == 0 || $i == 2) {
				$x = $x + 70;
			} else {
				$x = $x + 50;
			}
		}
		// Here is not the rand char but the matematical outcome
		$image_data['outcome'] 		= $math['outcome'];
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6)
	{	
	
		global $mainframe;
		$params	= &$mainframe->getParams();
		
		$charGroup = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
		if ($params->get( 'math_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $params->get( 'math_captcha_chars' ) ));
		}
		$charGroup = explode( ',', $charGroup );
	
		
		srand ((double) microtime() * 1000000);
		
		$random_string = "";
		
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			
			$random_string .= $charGroup[$random_char_group];
		}
		return $random_string;
	}

	function getRandomImage()
	{
		$rand = mt_rand(10,13);
		$image = ''.$rand.'.jpg';
		$image = JPATH_ROOT.DS.'components'.DS.'com_phocaguestbook'.DS.'assets'.DS.'captcha'.DS . $image;
		return $image;
	}


	function getRandomFontColor()
	{
		$rand = mt_rand(1,6);
		if ($rand == 1) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 2) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 153;}
		if ($rand == 3) {$font_color[0] = 0; $font_color[1] = 102; $font_color[2] = 0;}
		if ($rand == 4) {$font_color[0] = 102; $font_color[1] = 51; $font_color[2] = 0;}
		if ($rand == 5) {$font_color[0] = 163; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 6) {$font_color[0] = 0; $font_color[1] = 82; $font_color[2] = 163;}
		return $font_color;
	}
	
	function getMath()
	{
		$math['first'] 		= mt_rand(1,9);
		$math['second']		= mt_rand(1,9);
		$math['operation']	= mt_rand(10,13);

		switch ($math['operation'])
		{
			case 10;
				$math['outcome']	=  (int)$math['first'] + (int)$math['second'];
			break;
			
			case 11;
				if ((int)$math['first'] < (int)$math['second']) {
					$prevFirst		= $math['first'];
					$math['first'] 	= $math['second'];
					$math['second'] = $prevFirst;
				}
				
				$outcome = (int)$math['first'] - (int)$math['second'];
				if ($outcome == 0) {
					$math['second'] = $math['second'] - 1;
				}
				$math['outcome']	=  (int)$math['first'] - (int)$math['second'];
			break;
			
			case 12;
				$math['outcome']	=  (int)$math['first'] * (int)$math['second'];
			break;
			
			case 13;
				switch ($math['first'])
				{
					case 9:
						$second	= array(1,3,9,9);
					break;
					case 8:
						$second	= array(1,2,4,8);
					break;
					case 7:
						$second	= array(1,7,7,7);
					break;
					case 6:
						$second	= array(1,2,3,6);
					break;
					case 5:
						$second	= array(1,5,5,5);
					break;
					case 4:
						$second	= array(1,2,4,4);
					break;
					case 3:
						$second	= array(1,3,3,3);
					break;
					case 2:
						$second	= array(1,2,2,2);
					break;
					case 1:
					default:
						$second	= array(1,1,1,1);
					break;
				}
				$randSecond = mt_rand(0,3);
				$math['outcome']	= (int)$math['first'] / (int)$second[$randSecond];
				$math['second']		= (int)$second[$randSecond];// We must define the second new
			break;
		}
		return $math;
	}
}


// ================================================
class PhocaguestbookHelperCaptcha
{
	function createImageData()
	{
		$rand_char 			 = PhocaguestbookHelperCaptcha::generateRandomChar(6);
		$rand_char_array 	 = array (			$rand_char[0]."          ",
										   "  ".$rand_char[1]."        "	,
										 "    ".$rand_char[2]."      "	,
									   "      ".$rand_char[3]."    "   ,
									 "        ".$rand_char[4]."  "	,
								   "          ".$rand_char[5]);

		$image_name 		= PhocaguestbookHelperCaptcha::getRandomImage();
		$image 				= @imagecreatefromjpeg($image_name);
		 
		foreach ($rand_char_array as $key => $value)
		{
			$font_color 	= PhocaguestbookHelperCaptcha::getRandomFontColor();
			$position_x 	= mt_rand(5,8);
			$position_y 	= mt_rand(6,9);
			$font_size 		= mt_rand(4,5);
			
			ImageString($image, $font_size, $position_x, $position_y, $value, ImageColorAllocate ($image, $font_color[0], $font_color[1], $font_color[2]));
		}

		$image_data['outcome'] 		= $rand_char;
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6)
	{	
	
		global $mainframe;
		$params	= &$mainframe->getParams();
		
		$charGroup = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
		if ($params->get( 'standard_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $params->get( 'standard_captcha_chars' ) ));
		}
		$charGroup = explode( ',', $charGroup );
	
		
		srand ((double) microtime() * 1000000);
		
		$random_string = "";
		
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			
			$random_string .= $charGroup[$random_char_group];
		}
		return $random_string;
	}

	function getRandomImage()
	{
		$rand = mt_rand(1,4);
		$image = '0'.$rand.'.jpg';
		$image = JPATH_ROOT.DS.'components'.DS.'com_phocaguestbook'.DS.'assets'.DS.'captcha'.DS . $image;
		return $image;
	}


	function getRandomFontColor()
	{
		$rand = mt_rand(1,6);
		if ($rand == 1) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 2) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 153;}
		if ($rand == 3) {$font_color[0] = 0; $font_color[1] = 102; $font_color[2] = 0;}
		if ($rand == 4) {$font_color[0] = 102; $font_color[1] = 51; $font_color[2] = 0;}
		if ($rand == 5) {$font_color[0] = 163; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 6) {$font_color[0] = 0; $font_color[1] = 82; $font_color[2] = 163;}
		return $font_color;
	}
}



?>