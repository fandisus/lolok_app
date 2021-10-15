<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\TableComposer;

class PersonRoleDepartment {
  public static function deploySQLs() {
    $t = new TableComposer('roles');
    $t->string('role')->primary();
    $roleTable = $t->parse();

    $t = new TableComposer('departments');
    $t->string('department')->primary();
    $departmentTable = $t->parse();

    $t = new TableComposer('persons');
    $t->increments('id')->primary()
      ->integer('uid')->foreign('users', 'id', 'cascade','cascade')
      ->string('email')->unique()
      ->string('phone')->index()
      ->string('role')->foreign('roles','role', 'cascade')->index()
      ->string('department')->foreign('departments','department','cascade')->index();
    $personsTable = $t->parse();

    return array_merge($roleTable, $departmentTable, $personsTable);
  }
  public static function deploy() {
    foreach (self::deploySQLs() as $sql) DB::exec($sql,[]);
  }
  public static function undeploySQLs() {
    //Not yet implemented
    return [
      'DROP TABLE IF EXISTS persons',
      'DROP TABLE IF EXISTS departments',
      'DROP TABLE IF EXISTS roles'
    ];
  }
  public static function undeploy() {
    foreach (self::undeploySQLs() as $sql) DB::exec($sql, []);
  }
}