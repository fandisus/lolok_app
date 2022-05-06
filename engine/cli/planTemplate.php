<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\TableComposer;

class PLAN_NAME {
  public static function deploySQLs() {
    $t = new TableComposer('tableName');
    $t->increments('id')->primary()
      ->string('name', 50)->index()
      ->timestamp('dob')->index()
      ->string('email')->unique();
    return $t->parse();
  }
  public static function deploy() {
    foreach (self::deploySQLs() as $sql){
      if (substr($sql, 0, 2) == '--') continue;
      DB::exec($sql,[]);
    }
  }
  public static function undeploySQLs() {
    // return ['DROP TABLE IF EXISTS tableName'];
    return [];
  }
  public static function undeploy() {
    foreach (self::undeploySQLs() as $sql) DB::exec($sql, []);
  }
}