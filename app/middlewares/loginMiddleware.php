<?php
use Fandisus\Lolok\UserAgentInfo;
use Firebase\JWT\JWT;
use LolokApp\UserLogin;

$session = new stdClass();
$session->login = null;
if (isset($_COOKIE[JWT_NAME]) || isset($_POST[JWT_NAME])) {
  /* Logics:
  0. No need to read db for username and id.
  0. Just need to confirm jwt with db token.
  0. $session is stored in global variable. {"oJwt"=>POJO, "login"=>UserLogin, "access"=>Access}
  1. No cookie with <JWT_NAME>, then $session->login = null
  2. Verify JWT hash. If not verified, then client jwt is invalid. delCookies, softly forcing user to relogin
  3. When JWT hash verified, check JWT in db based on jwt, browser and platform(win/android/mac)
      if not found, then jwt is no longer valid. Just delCookies on client.
  4. When client JWT and db JWT differ, both JWT is possibly tampered. delete both (logout)
  5. When okay, pug UserLogin object to $session->login.
  */
  $jwt = (isset($_COOKIE[JWT_NAME])) ? $_COOKIE[JWT_NAME] : $_POST[JWT_NAME];
  try { $session->oJwt = JWT::decode($jwt, JWT_SECRET, [JWT_ALGO]); }
  catch (\Exception $ex) { UserLogin::delCookies(); }

  $info = new UserAgentInfo();
  $oUserLogin = UserLogin::findWhere('WHERE user_fk=:UID AND browser=:BROW AND platform=:PLAT', '*', [
    'UID'=>$session->oJwt->user, 'BROW'=>$info->browser, 'PLAT'=>$info->platform]);
  if ($oUserLogin === null) { UserLogin::delCookies(); }
  else {
    if ( $oUserLogin->jwt === $jwt) { $session->login = $oUserLogin; }
    else { $oUserLogin->logout(); }
  }
}
