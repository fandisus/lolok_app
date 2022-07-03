<?php

use LolokApp\Access;
use LolokApp\Helper\Session;

$session = Session::$obj;
//If not logged in, can not access user pages / resources
if ($session->login === null) header('location:'.WEBHOME);
$session->available_accesses = Access::ofUser($session->oJwt->user);
$session->currentAccess = Access::load($session->oJwt->access);

$menus = $session->currentAccess->getMenus();
// if ($login->username === 'admin') $menus = Access::availablePages();
// else {
//   $menus = $login->getMenuTree();
//   if ($menus === null) header('location:'.WEBHOME);
// }
// $_sidebarMenus = $menus;
// $_sidebarHomeLogo = LOGO_IMAGE;

?>