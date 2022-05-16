<?php
namespace LolokApp;

use Fandisus\Lolok\DB;
use Fandisus\Lolok\Debug;
use Fandisus\Lolok\JSONResponse;
use Fandisus\Lolok\Model;
use Fandisus\Lolok\UserAgentInfo;
use Firebase\JWT\JWT;

class User extends Model {
  protected static function tableName() { return 'users'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }

  public $id, $username, $email, $phone, $password, $fullname, $is_active;
  private $_access;

  public static function hashPassword($pass) { return hash('sha256', $pass); }

  // private function loadAccess() {
  //   if ($this->_access !== null) return $this->_access;
  //   $userAccess = UserAccess::find(['uid'=>$this->id]);
  //   $this->_accessProfile = AccessProfile::find(['name'=>$userAccess->profile]);
  //   return $this->_accessProfile;
  // }
  // public function getMenuTree() {
  //   if (!$this->loadAccess()) return null;
  //   return $this->_accessProfile->menu_tree;
  // }
  // public function getRights() {
  //   if (!$this->loadAccess()) return null;
  //   return $this->_accessProfile->getRights();
  // }
  // public function canAccess($href, $access='') {
  //   if ($this->username === 'admin') return true;

  //   $this->loadAccess();
  //   $ap = $this->_accessProfile;
  //   //Check href in accesses
  //   $filter = array_filter($ap->getRights(), function($a) use ($href) { return $a->href === $href; });
  //   if (count($filter) < 1) return false;
  //   //Check rights in access
  //   $menuItem = array_pop($filter);
  //   if (!isset($menuItem->rights) || !in_array($access, $menuItem->rights)) return false;
  //   return true;
  // }
  public function login() {
    if (!$this->is_active) JSONResponse::Error('User is inactive');
    //TODO: Might want to log login actions here.
    $info = new UserAgentInfo();
    //TODO: Might want to add SSO here
    DB::exec('DELETE FROM user_logins WHERE user_fk=:UID AND browser=:BROWSER AND platform=:PLATFORM',
      ['UID'=>$this->id, 'BROWSER'=>$info->browser, 'PLATFORM'=>$info->platform]
    );
    $jwt = JWT::encode(
      (object)["username"=>$this->username, "id"=>$this->id], //"email"=>$this->email  removed because might be security concern
      JWT_SECRET, JWT_ALGO
    );
    $uuid = DB::getOneVal('SELECT gen_random_uuid()');
    $oLogin = new UserLogin([]);
    $oLogin->id = $uuid;
    $oLogin->user_fk = $this->id;
    $oLogin->jwt = $jwt;
    $oLogin->ip = $info->ip;
    $oLogin->device = $info->device;
    $oLogin->platform = $info->platform;
    $oLogin->browser = $info->browser;
    $oLogin->created_at = date('Y-m-d H:i:s O');
    $oLogin->updated_at = date('Y-m-d H:i:s O');
    $oLogin->insert();

    setcookie(JWT_NAME, $jwt, 0, '','', false, true);
  }
  // public function logout() {
  //   //TODO: Might want to log logout actions here.
  //   if ($this->jwt != '') {
  //     $this->jwt = '';
  //     $this->update();
  //   }
  //   setcookie(JWT_NAME, '', time()-3600);
  // }
}