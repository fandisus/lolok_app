<?php

use Fandisus\Lolok\Debug;
use Fandisus\Lolok\UserAgentInfo;
use Firebase\JWT\JWT;
use LolokApp\User;
use LolokApp\UserLogin;

$login = null;
if (isset($_COOKIE[JWT_NAME])) {
  /* Logics:
  0. No need to read db for username and id.
  0. Just need to confirm jwt with db token.
  0. $login is stored in global variable. {"username"=>'', "id"=>X, "user_accesses"=>[] }
  1. No cookie with <JWT_NAME>, then $login = null
  2. When JWT verified, check JWT in db based on device and IP
  */
  function delCookie() { setcookie(JWT_NAME, '', time()-3600); }
  $jwt = $_COOKIE[JWT_NAME];
  try { $oJwtUser = JWT::decode($jwt, JWT_SECRET, [JWT_ALGO]); }
  catch (\Exception $ex) { delCookie(); }

  $info = new UserAgentInfo();
  $oUserLogin = UserLogin::findWhere('WHERE user_fk=:UID AND browser=:BROW AND platform=:PLAT', '*', [
    'UID'=>$oJwtUser->id, 'BROW'=>$info->browser, 'PLAT'=>$info->platform]);
  if ($oUserLogin === null) { delCookie(); }
  else {
    if ( $oUserLogin->jwt === $jwt) { $login=$oUserLogin; }
    else { delCookie(); $oUserLogin->delete(); }
  }
}
