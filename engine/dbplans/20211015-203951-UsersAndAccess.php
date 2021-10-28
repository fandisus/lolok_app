<?php
use Fandisus\Lolok\DB;
use Fandisus\Lolok\TableComposer;

class UsersAndAccess {
  public static function deploySQLs() {
    $t = new TableComposer('settings');
    $t->string('name')->primary()
      ->string('setting_value');
    $settingTable = $t->parse();

    $t = new TableComposer('users');
    $t->increments('id')->primary()
      ->string('username')->unique()
      ->string('password',100)
      ->string('fullname',100)->index()
      ->string('email')->unique()
      ->string('phone')->index()
      ->string('jwt', 200);
    $userTable = $t->parse();

    $t = new TableComposer('access_profile');
    $t->string('name', 50)->primary()
      ->text('menu_tree');
    //Future can add regions, areas, departments etc.
    $accessProfileTable = $t->parse();

    $t = new TableComposer('user_accesses');
    $t->integer('uid')->foreign('users','id','cascade','cascade')
      ->string('profile')->foreign('access_profile', 'name', 'cascade','cascade');
    $userAccessTable = $t->parse();

    return array_merge( $settingTable, $userTable, $accessProfileTable, $userAccessTable );
  }
  public static function deploy() {
    foreach (self::deploySQLs() as $sql) DB::exec($sql,[]);
  }
  public static function undeploySQLs() {
    //Not yet implemented
    return [
      'DROP TABLE IF EXISTS user_accesses',
      'DROP TABLE IF EXISTS access_profile',
      'DROP TABLE IF EXISTS users',
      'DROP TABLE IF EXISTS settings'
    ];
  }
  public static function undeploy() {
    foreach (self::undeploySQLs() as $sql) DB::exec($sql, []);
  }
}