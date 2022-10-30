<?php
namespace LolokApp;

use Fandisus\Lolok\DB;
use Fandisus\Lolok\Model;
use Fandisus\Lolok\UserAgentInfo;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
  public function delPreviousLogin() {
    DB::exec('DELETE FROM user_logins WHERE user_fk=:UID AND browser=:BROWSER AND platform=:PLATFORM',
      ['UID'=>$this->user_fk, 'BROWSER'=>$this->browser, 'PLATFORM'=>$this->platform]
    );
  }
  public static function createNew($user_fk, $access_pk) {
    if ($access_pk !== null) {
      $hasAccess = DB::rowExists('SELECT id FROM user_accesses WHERE user_fk=:UID AND access_fk=:AID', ['UID'=>$user_fk, 'AID'=>$access_pk]);
      if (!$hasAccess) throw new \Exception('Invalid user access');
    } else {
      $firstAccess = DB::getOneVal('SELECT access_fk FROM user_accesses WHERE user_fk=:UID',['UID'=>$user_fk]);
      if ($firstAccess === null) throw new \Exception('User has no user access');
      $access_pk = $firstAccess;
    }

    $res = new self([]);
    $info = new UserAgentInfo();
    $res->id = DB::getOneVal('SELECT gen_random_uuid()');
    $res->user_fk = $user_fk;
    $res->jwt = JWT::encode(["user"=>$user_fk, 'access'=>$access_pk], JWT_SECRET, JWT_ALGO);
    $res->ip = $info->ip;
    $res->device = $info->device;
    $res->platform = $info->platform;
    $res->browser = $info->browser;
    $res->created_at = date('Y-m-d H:i:s O');
    $res->updated_at = date('Y-m-d H:i:s O');
    return $res;
  }
  public static function decodeJWT($jwt) {
    return JWT::decode($jwt, new Key(JWT_SECRET, JWT_ALGO));
  }
}