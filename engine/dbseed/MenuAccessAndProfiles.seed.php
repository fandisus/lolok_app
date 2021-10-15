<?php
use LolokApp\Menu;
use LolokApp\MenuAccess;
use LolokApp\MenuAccessProfile;

class MenuAccessAndProfiles {
  public static function run() {
    $menus = [
      (object)['name'=>'user_management', 'position'=>1, 'text'=>'User Management', 'icon'=>'users', 'href'=>'user/user-management', 'parent'=>null],
    ];
    Menu::multiInsert($menus);

    $menuAccesses = [
      (object) ['id'=>1, 'menu'=>'user_management', 'akses'=>'enter'],
      (object) ['id'=>2, 'menu'=>'user_management', 'akses'=>'getUsers'],
      (object) ['id'=>3, 'menu'=>'user_management', 'akses'=>'saveUser'],
      (object) ['id'=>4, 'menu'=>'user_management', 'akses'=>'delUser'],
      (object) ['id'=>5, 'menu'=>'user_management', 'akses'=>'changePass'],
      (object) ['id'=>6, 'menu'=>'user_management', 'akses'=>'getAclProfiles'],
      (object) ['id'=>7, 'menu'=>'user_management', 'akses'=>'saveAclProfile'],
      (object) ['id'=>8, 'menu'=>'user_management', 'akses'=>'delAclProfile'],
    ];
    MenuAccess::multiInsert($menuAccesses);

    $menuProfiles = [
      (object) ['profile'=>'All', 'id_menu_akses'=>1],
      (object) ['profile'=>'All', 'id_menu_akses'=>2],
      (object) ['profile'=>'All', 'id_menu_akses'=>3],
      (object) ['profile'=>'All', 'id_menu_akses'=>4],
      (object) ['profile'=>'All', 'id_menu_akses'=>5],
      (object) ['profile'=>'All', 'id_menu_akses'=>6],
      (object) ['profile'=>'All', 'id_menu_akses'=>7],
      (object) ['profile'=>'All', 'id_menu_akses'=>8],
    ];
    MenuAccessProfile::multiInsert($menuProfiles);
  }
}