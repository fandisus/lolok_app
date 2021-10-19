<?php
// $login is set in middlewares/all.php
use LolokApp\UserAccess;
if (!isset($login)) header('location:'.WEBHOME);

$menus = [];
if ($login->username === 'admin') $menus = UserAccess::availableMenus();
else {
  $menus = $login->getMenuTree();
  if ($menus === null) header('location:'.WEBHOME);
}
$_sidebarMenus = $menus;
$_sidebarHomeLogo = LOGO_IMAGE;

?>