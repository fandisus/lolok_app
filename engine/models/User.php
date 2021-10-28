<?php
namespace LolokApp;

use Fandisus\Lolok\Debug;
use Fandisus\Lolok\Model;
use Firebase\JWT\JWT;

class User extends Model {
  protected static function tableName() { return 'users'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }

  public $id, $username, $password, $fullname, $email, $phone, $jwt;
  private $_accessProfile;

  public static function hashPassword($pass) { return hash('sha256', $pass); }

  private function loadAccess() {
    if ($this->_accessProfile !== null) return $this->_accessProfile;
    $userAccess = UserAccess::find(['uid'=>$this->id]);
    $this->_accessProfile = AccessProfile::find(['name'=>$userAccess->profile]);
    return $this->_accessProfile;
  }
  public function getMenuTree() {
    if (!$this->loadAccess()) return null;
    return $this->_accessProfile->menu_tree;
  }
  public function getRights() {
    if (!$this->loadAccess()) return null;
    return $this->_accessProfile->getRights();
  }
  public function canAccess($href, $access='') {
    if ($this->username === 'admin') return true;

    $this->loadAccess();
    $ap = $this->_accessProfile;
    //Check href in accesses
    $filter = array_filter($ap->getRights(), function($a) use ($href) { return $a->href === $href; });
    if (count($filter) < 1) return false;
    //Check rights in access
    $menuItem = array_pop($filter);
    if (!isset($menuItem->rights) || !in_array($access, $menuItem->rights)) return false;
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