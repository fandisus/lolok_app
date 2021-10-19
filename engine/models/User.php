<?php
namespace LolokApp;

use Fandisus\Lolok\DB;
use Fandisus\Lolok\MenuAccess;
use Fandisus\Lolok\Model;
use Firebase\JWT\JWT;

class User extends Model {
  protected static function tableName() { return 'users'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }

  public $id, $username, $password, $email, $phone, $jwt;
  private $_menuAccess;

  public static function hashPassword($pass) { return hash('sha256', $pass); }

  private function loadAccess() {
    if ($this->_menuAccess !== null) return;
    return $this->_menuAccess = UserAccess::find(['uid'=>$this->id]);
  }
  public function getMenuTree() {
    if (!$this->loadAccess()) return null;
    return $this->_menuAccess->getMenuTree();
  }
  public function canAccess($href, $access='') {
    if ($this->username === 'admin') return true;

    $this->loadAccess();
    //Check href in accesses
    $filter = array_filter($this->_menuAccess->accesses, function($a) use ($href) { return $a->href === $href; });
    if (count($filter) < 1) return false;
    //Check rights in access
    if (!isset($filter[0]->rights) || !in_array($access, $filter[0]->rights)) return false;
    return true;
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
    if ($this->jwt != '') {
      $this->jwt = '';
      $this->update();
    }
    setcookie(JWT_NAME, '', time()-3600);
  }
}