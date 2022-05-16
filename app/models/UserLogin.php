<?php
namespace LolokApp;

use Fandisus\Lolok\Model;

class UserLogin extends Model {
  protected static function tableName() { return 'user_logins'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return false; }
  protected static function jsonColumns() { return []; }
  public $id, $user_fk, $jwt, $ip, $device, $platform, $browser, $created_at, $updated_at;
  public static function delCookies() {
    setcookie(JWT_NAME, '', time()-3600);
  }
  public function logout() {
    UserLogin::delCookies();
    $this->delete();
  }
}