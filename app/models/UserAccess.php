<?php
namespace LolokApp;

use Fandisus\Lolok\Model;
/*This class represents who can access what. */
class UserAccess extends Model {
  protected static function tableName() { return 'user_accesses'; }
  protected static function PK() { return ['uid']; }
  protected static function hasSerial() { return false; }
  protected static function jsonColumns() { return []; }

  public $uid, $profile;
}