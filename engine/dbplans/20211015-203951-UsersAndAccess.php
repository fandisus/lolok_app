<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\TableComposer;

class UsersAndAccess {
  public static function deploySQLs() {
    $res = [];
    $t = new TableComposer('settings');
    $t->string('name')->primary()
      ->string('setting_value');
    $settingTable = $t->parse();

    $t = new TableComposer('menus');
    $t->string('name')->primary()
      ->string('text', 100)->notNull()
      ->string('href', 200)->notNull()
      ->string('icon')->notNull()
      ->string('parent')->foreign('menus','name','cascade','cascade');
    $menusTable = $t->parse();

    $t = new TableComposer('menu_accesses');
    $t->increments('id')->primary()
      ->string('menu')->foreign('menus', 'name', 'cascade', 'cascade')
      ->string('akses')
      ->unique('menu, akses');
    $menuAccessTable = $t->parse();

    $t = new TableComposer('menu_access_profiles');
    $t->string('profile')->index()
      ->integer('id_menu_akses')->foreign('menu_accesses', 'id', 'cascade','cascade')
      ->primary('profile, id_menu_akses');
    $accessProfile = $t->parse();

    $t = new TableComposer('users');
    $t->increments('id')->primary()
      ->string('username')->unique()
      ->string('password',100)
      ->string('email')->unique()
      ->string('phone')->index();
    $userTable = $t->parse();

    $t = new TableComposer('user_accesses');
    $t->integer('uid')->foreign('users','id','cascade','cascade')
      ->string('profile');
    $userAccessTable = $t->parse();


    return array_merge(
      $settingTable, $menusTable, $menuAccessTable, $accessProfile, $userTable, $userAccessTable
    );
  }
  public static function deploy() {
    foreach (self::deploySQLs() as $sql) DB::exec($sql,[]);
  }
  public static function undeploySQLs() {
    //Not yet implemented
    return [
      'DROP TABLE IF EXISTS user_accesses',
      'DROP TABLE IF EXISTS users',
      'DROP TABLE IF EXISTS menu_access_profiles',
      'DROP TABLE IF EXISTS menu_accesses',
      'DROP TABLE IF EXISTS menus',
      'DROP TABLE IF EXISTS settings'
    ];
  }
  public static function undeploy() {
    foreach (self::undeploySQLs() as $sql) DB::exec($sql, []);
  }
}