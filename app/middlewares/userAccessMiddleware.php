<?php

use LolokApp\Access;
use LolokApp\User;

//If not logged in, can not access user pages / resources
if ($session->login === null) header('location:'.WEBHOME);
$session->available_access = Access::ofUser($session->oJwt->user);

//If first time login, no access chosen, just get first one
if ($session->oJwt->access === null) {
  //if no access, log him out
  if (count($session->available_access) === 0) { $session->login->logout(); header('location:'.WEBHOME.'login'); }
  //set session access
  $firstAccess = $session->available_access[0];
  $oUser = User::find(['id'=>$session->oJwt->user]);
  $oUser->login($firstAccess->id);
  //Put access_fk to session for further loading.
  $session->oJwt->access = $firstAccess->id;
}

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