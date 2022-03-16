<?php
use Firebase\JWT\JWT;
use LolokApp\User;

if (isset($_COOKIE[JWT_NAME])) {
  try { $oUser = JWT::decode($_COOKIE[JWT_NAME], JWT_SECRET, ['HS256']); }
  catch (\Exception $ex) { setcookie(JWT_NAME, '', time()-3600); }

  $dbUser = User::find(['id'=>$oUser->id]);
  if ($dbUser === null) { setcookie(JWT_NAME, '', time()-3600); }
  elseif ($dbUser->jwt !== $_COOKIE[JWT_NAME]) $dbUser->logout();
  else $login = $dbUser;
}
