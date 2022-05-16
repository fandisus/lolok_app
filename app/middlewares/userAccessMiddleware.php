<?php

use LolokApp\Access;
use LolokApp\User;

//If not logged in, can not access user pages / resources
if ($session->login === null) header('location:'.WEBHOME);
$oUser = User::find(['id'=>$session->oJwt->user]);
$session->user = $oUser;
$session->available_accesses = Access::ofUser($session->oJwt->user);
$session->access = Access::load($session->oJwt->access);

$menus = [];
// if ($login->username === 'admin') $menus = Access::availablePages();
// else {
//   $menus = $login->getMenuTree();
//   if ($menus === null) header('location:'.WEBHOME);
// }
// $_sidebarMenus = $menus;
// $_sidebarHomeLogo = LOGO_IMAGE;

?>