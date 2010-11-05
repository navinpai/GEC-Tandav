<?php
/*********** XML PARAMETERS AND VALUES ************/
$xml_item = "component";// component | template
$xml_file = "phocaguestbook.xml";		
$xml_name = "PhocaGuestbook";
$xml_creation_date = "19/07/2010";
$xml_author = "Jan Pavelka (www.phoca.cz)";
$xml_author_email = "";
$xml_author_url = "www.phoca.cz";
$xml_copyright = "Jan Pavelka";
$xml_license = "GNU/GPL";
$xml_version = "1.4.2";
$xml_description = "Phoca Guestbook";
$xml_copy_file = 1;//Copy other files in to administration area (only for development), ./front, ./language, ./other

$xml_menu = array (0 => "Phoca Guestbook", 1 => "option=com_phocaguestbook");
$xml_submenu[0] = array (0 => "Phoca Items", 1 => "option=com_phocaguestbook&amp;view=phocaguestbooks");
$xml_submenu[1] = array (0 => "Phoca Guestbooks", 1 => "option=com_phocaguestbook&amp;view=phocaguestbookbs");


$xml_menu = array (0 => "Phoca Guestbook", 1 => "option=com_phocaguestbook", 2 => "components/com_phocaguestbook/assets/images/icon-16-pgb-menu.png");
$xml_submenu[0] = array (0 => "Phoca Control Panel", 1 => "option=com_phocaguestbook", 2 => "components/com_phocaguestbook/assets/images/icon-16-pgb-menu-cp.png");
$xml_submenu[1] = array (0 => "Phoca Items", 1 => "option=com_phocaguestbook&amp;view=phocaguestbooks", 2 => "components/com_phocaguestbook/assets/images/icon-16-pgb-menu-item.png");
$xml_submenu[2] = array (0 => "Phoca Guestbooks", 1 => "option=com_phocaguestbook&amp;view=phocaguestbookbs", 2 => "components/com_phocaguestbook/assets/images/icon-16-pgb-menu-guestbook.png");
$xml_submenu[3] = array (0 => "Phoca Info", 1 => "option=com_phocaguestbook&amp;view=phocaguestbookin", 2 => "components/com_phocaguestbook/assets/images/icon-16-pgb-menu-info.png");


$xml_install_file = 'install.phocaguestbook.php'; 
$xml_uninstall_file = 'uninstall.phocaguestbook.php';
/*********** XML PARAMETERS AND VALUES ************/
?>