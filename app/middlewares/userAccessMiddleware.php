<?php
// $login is set in middlewares/all.php

if ($login === null) header('location:'.WEBHOME);

$menus = [];
if ($login->username === 'admin') $menus = AccessProfile::availableMenus();
else {
  $menus = $login->getMenuTree();
  if ($menus === null) header('location:'.WEBHOME);
}
$_sidebarMenus = $menus;
$_sidebarHomeLogo = LOGO_IMAGE;

?>