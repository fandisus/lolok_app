<?php
namespace LolokApp;

use Fandisus\Lolok\Model;

class UserToken extends Model {
  protected static function tableName() { return 'user_tokens'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }
  public $id, $user_fk, $type, $action, $token, $expiry;
}