<?php
namespace LolokApp;
use Fandisus\Lolok\Model;

class User extends Model {
  protected static function tableName() { return 'users'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }

  public $id, $username, $password, $email, $phone;

  public static function hashPassword($pass) { return hash('sha256', $pass); }
}