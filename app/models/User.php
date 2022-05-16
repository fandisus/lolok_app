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

  public function login($access_pk=null) {
    //WHY JWT: Session will burden server, while JWT does not.
    //Concern: JWT is lengthy, but it's ok as long as we does not store too much data.
    //         For userpk and accesspk, it should be less than 200 chars (132)
    if (!$this->is_active) JSONResponse::Error('User is inactive');
    //TODO: Might want to log login actions here.
    $info = new UserAgentInfo();
    //TODO: Might want to add SSO here
    DB::exec('DELETE FROM user_logins WHERE user_fk=:UID AND browser=:BROWSER AND platform=:PLATFORM',
      ['UID'=>$this->id, 'BROWSER'=>$info->browser, 'PLATFORM'=>$info->platform]
    );
    $jwt = JWT::encode(
      (object)["user"=>$this->id, 'access'=>$access_pk], JWT_SECRET, JWT_ALGO
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
}