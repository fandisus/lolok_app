<?php
namespace LolokApp;

use Fandisus\Lolok\Model;
/*This class represents who can access what. */
class UserAccess extends Model {
  protected static function tableName() { return 'user_accesses'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }

  public $id, $user_fk, $access_fk, $created_at, $updated_at;
}