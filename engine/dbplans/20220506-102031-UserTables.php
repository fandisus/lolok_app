<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\TableComposer;

class UserTables {
  public static function deploySQLs() {
    $t = new TableComposer('users');
    $t->bigIncrements('id')->primary()
      ->string('username')->unique()
      ->string('email')->unique()
      ->string('phone')->index()
      ->string('password',100)
      ->string('fullname',100)->index()
      ->bool('is_active');
    $userTable = $t->parse();

    $t = new TableComposer('user_logins');
    $t->string('id')->primary()
      ->bigInteger('user_fk')->foreign('users', 'id', 'cascade', 'cascade')->index()
      ->string('jwt')->index()
      ->string('ip', 20)
      ->string('device')
      ->string('platform')
      ->string('browser')
      ->timestamp('created_at')
      ->timestamp('updated_at');
    $loginsTable = $t->parse();

    $t = new TableComposer('user_tokens');
    $t->bigIncrements('id')->primary()
      ->bigInteger('user_fk')->foreign('users', 'id', 'cascade', 'cascade')->index()
      ->string('type', 10) // email or phone
      ->string('action', 10) // resetpass, confirm, link
      ->string('token')->index()
      ->timestamp('expiry');
    $userTokensTable = $t->parse();
    
    return array_merge( $userTable, $loginsTable, $userTokensTable );
  }
  public static function deploy() {
    foreach (self::deploySQLs() as $sql){
      if (substr($sql, 0, 2) == '--') continue;
      DB::exec($sql,[]);
    }
  }
  public static function undeploySQLs() {
    return [
      'DROP TABLE IF EXISTS user_tokens',
      'DROP TABLE IF EXISTS user_logins',
      'DROP TABLE IF EXISTS users'
    ];
  }
  public static function undeploy() {
    foreach (self::undeploySQLs() as $sql) DB::exec($sql, []);
  }
}