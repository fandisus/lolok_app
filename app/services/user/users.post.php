<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\JSONResponse;
use LolokApp\AccessProfile;
use LolokApp\User;
use LolokApp\UserAccess;

if (!function_exists($_POST['a'])) JSONResponse::Error("Service {$_POST['a']} not available yet");
else $_POST['a']();

function init() {
  if (!$GLOBALS['login']->canAccess(APP_PATH, 'read')) JSONResponse::Error('Access denied');
  try {
    $dbres = AccessProfile::all('name');
    $accessProfiles = array_map(function($row) { return $row->name; }, $dbres);
  
    $sql = <<<SQL
      SELECT u.id, u.username, u.fullname, u.email, u.phone, ua.profile accessProfile
      FROM users u
      LEFT JOIN user_accesses ua ON ua.uid=u.id
      ORDER BY u.username
SQL;
    $users = DB::get($sql);
    JSONResponse::Success(['accessProfiles'=>$accessProfiles, 'users'=>$users]);
  } catch (\Exception $ex) { JSONResponse::Error($ex->getMessage()); }
}

function saveUser() {
  if (!$GLOBALS['login']->canAccess(APP_PATH, 'create')) JSONResponse::Error('Access denied');
  $postUser = json_decode($_POST['u']);
  if (trim($postUser->username) === '') JSONResponse::Error('Username is required');
  if (trim($postUser->fullname) === '') JSONResponse::Error('Full name is required');

  try {
    if ($postUser->id === 0) {
      $oUser = User::findWhere('WHERE username=:USER', '*', ['USER'=>$postUser->username]);
      if ($oUser) JSONResponse::Error('Username already taken');
    } else {
      $oUser = User::findWhere('WHERE username=:USER AND id<>:ID', '*', ['USER'=>$postUser->username, 'ID'=>$postUser->id]);
      if ($oUser) JSONResponse::Error('Username already taken');
    }
    if ($postUser->pass !== $postUser->cpass) JSONResponse::Error('Password confirmation incorrect');
  
    $ap = AccessProfile::find(['name'=>$postUser->accessProfile]);
    if (!$ap) JSONResponse::Error('Invalid access profile');
  
    if ($postUser->id === 0) {
      $oUser = new User($postUser);
      $oUser->password = User::hashPassword($postUser->pass);
      $oUser->insert(); //Auto generate id, will be put into $oUser->id
  
      $ua = new UserAccess(['uid'=>$oUser->id, 'profile'=>$postUser->accessProfile]);
      $ua->insert();
  
      unset($oUser->password, $oUser->jwt);
      $oUser->accessProfile = $postUser->accessProfile;
      JSONResponse::Success(['u'=>$oUser]);
    } else {
      $oUser = User::find(['id'=>$postUser->id]);
      if (!$oUser) JSONResponse::Error('User not found');
      $oUser->username = $postUser->username;
      $oUser->email = $postUser->email;
      $oUser->phone = $postUser->phone;
      $oUser->fullname = $postUser->fullname;
      
      $ua = UserAccess::findWhere('WHERE uid=:UID', '*', ['UID'=>$postUser->id]);
      if (!$ua) JSONResponse::Error('User Access data not found');
      $ua->profile = $postUser->accessProfile;

      $updateUser = false;
      try { $oUser->update(); $updateUser = true; } catch (\Exception $ex) {}
      try { $ua->update(); $updateUser = true; } catch (\Exception $ex) {}
      if (!$updateUser) JSONResponse::Error('No changes in data');
      unset($oUser->password, $oUser->jwt);
      $oUser->accessProfile = $postUser->accessProfile;
      JSONResponse::Success(['u'=>$oUser]);
    }
  } catch (\Exception $ex) { JSONResponse::Error($ex->getMessage()); }
}

function changePass() {
  $uid = $_POST['uid'];
  $pass = $_POST['pass'];
  try {
    $oUser = User::find(['id'=>$uid]);
    if (!$oUser) JSONResponse::Error('User not found');
    $oUser->password = User::hashPassword($pass);
    $oUser->update();
    JSONResponse::Success();
  } catch (\Exception $ex) { JSONResponse::Error($ex->getMessage()); }
}

function delUser() {
  $uid = $_POST['target'];
  try {
    $oUser = User::find(['id'=>$uid]);
    if (!$oUser) JSONResponse::Error('User not found');
    if (User::count() === 1) JSONResponse::Error('Please dont delete all users');
    $oUser->delete();
    JSONResponse::Success();
  } catch (\Exception $ex) { JSONResponse::Error($ex->getMessage()); }
}