<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\TableComposer;

class SettingsTables {
  public static function deploySQLs() {
    $t = new TableComposer('settings');
    $t->string('name')->primary()
      ->string('setting_value');
    return $t->parse();
  }
  public static function deploy() {
    foreach (self::deploySQLs() as $sql){
      if (substr($sql, 0, 2) == '--') continue;
      DB::exec($sql,[]);
    }
  }
  public static function undeploySQLs() {
    return ['DROP TABLE IF EXISTS settings'];
  }
  public static function undeploy() {
    foreach (self::undeploySQLs() as $sql) DB::exec($sql, []);
  }
}