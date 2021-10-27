<?php
use Fandisus\Lolok\JSONResponse;
use LolokApp\User;

if (!function_exists($_POST['a'])) JSONResponse::Error("Service {$_POST['a']} not available yet");
else $_POST['a']();

function changePassword() {
  $login = $GLOBALS['login'];
  $oldpass = $_POST['oldpass'];
  $pass = $_POST['pass'];
  $cpass = $_POST['cpass'];
  if ($login->password !== User::hashPassword($oldpass)) JSONResponse::Error('Old password is incorrect');
  if ($pass !== $cpass) JSONResponse::Error('Password confirmation error');
  if ($pass === '') JSONResponse::Error('Password can not be empty');
  if (strlen($pass) < 5) JSONResponse::Error('Minimum password length: 5');

  try {
    $login->password = User::hashPassword($pass);
    $login->update();
    JSONResponse::Success();
  } catch (\Exception $ex) { JSONResponse::Error($ex->getMessage()); }
}