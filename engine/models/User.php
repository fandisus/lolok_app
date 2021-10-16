<?php
namespace LolokApp;
use Fandisus\Lolok\Model;
use Firebase\JWT\JWT;

class User extends Model {
  protected static function tableName() { return 'users'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }

  public $id, $username, $password, $email, $phone, $jwt;

  public static function hashPassword($pass) { return hash('sha256', $pass); }
  public function updateLoginInfo() {

  }
  public function getAcl() {

  }
  public function canAccess($menuName, $access='') {
    if ($this->username === 'admin') return true;
  }
  public function login() {
    //TODO: Might want to log login actions here.
    $jwt = JWT::encode(
      (object)["username"=>$this->username, "id"=>$this->id], //"email"=>$this->email  removed because might be security concern
      JWT_SECRET
    );
    $this->jwt = $jwt;
    $this->update();
    setcookie(JWT_NAME, $jwt, 0, '','', false, true);
  }
  public function logout() {
    //TODO: Might want to log logout actions here.
    $this->jwt = '';
    $this->update();
    setcookie(JWT_NAME, '', time()-3600);
  }
}