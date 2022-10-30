<?php
namespace LolokApp;

use Fandisus\Lolok\JSONResponse;
use Fandisus\Lolok\Model;
use Fandisus\Lolok\UserAgentInfo;


class User extends Model {
  protected static function tableName() { return 'users'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }

  public $id, $username, $email, $phone, $password, $fullname, $is_active;

  public static function hashPassword($pass) { return hash('sha256', $pass); }

  public function login($access_pk=null) {
    //WHY JWT: Session will burden server, while JWT does not.
    //Concern: JWT is lengthy, but it's ok as long as we does not store too much data.
    //         For userpk and accesspk, it should be less than 200 chars (132)
    if (!$this->is_active) throw new \Exception('User is inactive');
    //TODO: Might want to log login actions here.
    $info = new UserAgentInfo();
    //TODO: Might want to add SSO here
    $oLogin = UserLogin::createNew($this->id, $access_pk);
    $oLogin->delPreviousLogin();
    $oLogin->insert();

    setcookie(JWT_NAME, $oLogin->jwt, 0, '','', false, true);
  }
}