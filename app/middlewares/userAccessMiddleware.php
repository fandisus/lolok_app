<?php

use Fandisus\Lolok\JSONResponse;
use LolokApp\Access;
use LolokApp\Helper\Session;

//If not logged in, can not access user pages / resources
if (Session::$login === null) header('location:'.WEBHOME);
Session::$available_accesses = Access::ofUser(Session::$oJwt->user);
Session::$currentAccess = Access::load(Session::$oJwt->access);

$menus = Session::$currentAccess->getMenus();

if (!Session::$currentAccess->canAccess(APP_PATH)) {
  if ($_SERVER['REQUEST_METHOD'] === 'GET') header('location:'.WEBHOME.'user/403');
  else JSONResponse::Error('Access denied');
}
// if ($login->username === 'admin') $menus = Access::availablePages();
// else {
//   $menus = $login->getMenuTree();
//   if ($menus === null) header('location:'.WEBHOME);
// }
// $_sidebarMenus = $menus;
// $_sidebarHomeLogo = LOGO_IMAGE;

?>