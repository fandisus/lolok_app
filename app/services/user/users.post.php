<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\JSONResponse;
use LolokApp\Access;
use LolokApp\User;
use LolokApp\UserAccess;

if (!function_exists($_POST['a'])) JSONResponse::Error("Service {$_POST['a']} not available yet");
else $_POST['a']();

function init() {
  try {
    $dbres = Access::all('name');
    $accesses = array_map(function($row) { return $row->name; }, $dbres);
  
    $sql = <<<SQL
      SELECT u.id, u.username, u.fullname, u.email, u.phone, string_agg(a."name", ',' order by a."name") accesses
      FROM users u
      LEFT JOIN user_accesses ua ON u.id = ua.user_fk
      LEFT JOIN accesses a ON a.id = ua.access_fk
      GROUP BY u.id
      ORDER BY u.username
SQL;
    $users = DB::get($sql);
    foreach ($users as &$u) $u->accesses = explode(',', $u->accesses);
    JSONResponse::Success(['accesses'=>$accesses, 'users'=>$users]);
  } catch (\Exception $ex) { JSONResponse::Error($ex->getMessage()); }
}

function saveUser() {
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
  
    if (!count($postUser->accesses)) JSONResponse::Error('Please choose one user access');

    $strAccesses = "'".implode("','", $postUser->accesses)."'";
    $checkAccess = Access::allPlus("WHERE name IN ($strAccesses)");
    if (count($checkAccess) !== count($postUser->accesses)) JSONResponse::Error('Invalid user access. Please refresh the page and try again');
  
    if ($postUser->id === 0) {
      $postUser->is_active = true;
      $oUser = new User($postUser);
      $oUser->password = User::hashPassword($postUser->pass);
      $oUser->insert(); //Auto generate id, will be put into $oUser->id
  
      foreach ($checkAccess as $acc) {
        $ua = new UserAccess(['user_fk'=>$oUser->id, 'access_fk'=>$acc->id]);
        $ua->insert();
      }
  
      unset($oUser->password, $oUser->jwt);
      $oUser->accesses = $postUser->accesses;
      JSONResponse::Success(['u'=>$oUser]);
    } else {
      $oUser = User::find(['id'=>$postUser->id]);
      if (!$oUser) JSONResponse::Error('User not found');
      // $oUser->username = $postUser->username;
      $oUser->email = $postUser->email;
      $oUser->phone = $postUser->phone;
      $oUser->fullname = $postUser->fullname;
      

      try { $oUser->update(); $updateUser = true; } catch (\Exception $ex) {}
      DB::exec('DELETE FROM user_accesses WHERE user_fk=:UFK', ['UFK'=>$postUser->id]);
      foreach ($checkAccess as $acc) {
        $ua = new UserAccess(['user_fk'=>$oUser->id, 'access_fk'=>$acc->id]);
        $ua->insert();
      }
      unset($oUser->password, $oUser->jwt);
      $oUser->accesses = $postUser->accesses;
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