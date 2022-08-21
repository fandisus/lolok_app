<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\TableComposer;

class UserAccessTables {
  public static function deploySQLs() {
    $t = new TableComposer('accesses');
    $t->bigIncrements('id')->primary()
      ->string('name',20)->unique()
      ->string('role')
      ->timestampTz('created_at')
      ->timestampTz('updated_at');
    $accessTable = $t->parse();

    $t = new TableComposer('access_pages');
    $t->bigInteger('access_fk')->foreign('accesses', 'id', 'cascade', 'cascade')
      ->string('url')
      ->primary(['access_fk', 'url'])
      ->jsonb('rights');
    $accessPagesTable = $t->parse();

    $t = new TableComposer('user_accesses');
    $t->bigIncrements('id')->primary()
      ->bigInteger('user_fk')->foreign('users', 'id', 'cascade', 'cascade')->index()
      ->bigInteger('access_fk')->foreign('accesses', 'id', 'cascade', 'set null')->index()
      ->timestampTz('created_at')
      ->timestampTz('updated_at');
    $userAccessTable = $t->parse();

    return array_merge( $accessTable, $accessPagesTable, $userAccessTable );
  }
  public static function deploy() {
    foreach (self::deploySQLs() as $sql){
      if (substr($sql, 0, 2) == '--') continue;
      DB::exec($sql,[]);
    }
  }
  public static function undeploySQLs() {
    return [
      'DROP TABLE IF EXISTS user_accesses',
      'DROP TABLE IF EXISTS access_pages',
      'DROP TABLE IF EXISTS accesses',
    ];
  }
  public static function undeploy() {
    foreach (self::undeploySQLs() as $sql) DB::exec($sql, []);
  }
}