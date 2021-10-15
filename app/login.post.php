<?php

use Fandisus\Lolok\JSONResponse;
use LolokApp\User;

$username = $_POST['username'];
$password = $_POST['password'];
$oUser = User::findWhere('WHERE username=:USER', '*', ['USER'=>$username]);
if ($oUser === null) JSONResponse::Error('User or password incorrect');
if ($oUser->password !== User::hashPassword($password)) JSONResponse::Error('User or password incorrect');

$oUser->login();
JSONResponse::Success();

?>