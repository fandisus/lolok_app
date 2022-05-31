<?php

use Fandisus\Lolok\JSONResponse;
use LolokApp\User;

$username = $_POST['username'];
$password = $_POST['password'];
$oUser = User::findWhere('WHERE :USER IN (username, email, phone)', '*', ['USER'=>$username]);
if ($oUser === null) JSONResponse::Error('User or password incorrect');
if ($oUser->password !== User::hashPassword($password)) JSONResponse::Error('User or password incorrect');

try { $oUser->login(); }
catch (\Throwable $th) { JSONResponse::Error($th->getMessage()); }

JSONResponse::Success();

?>