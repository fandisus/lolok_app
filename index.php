<?php
require 'vendor/autoload.php';

use Fandisus\Lolok\Files;
use Fandisus\Lolok\JSONResponse;

$files = Files::GetDirFiles(__DIR__.'/engine/config');
foreach ($files as $v) include($v);

//After getting APPNAMESPACE from engine/config/constants.php --> config.json, autoload models:
include DIR."/engine/models/autoload.php";

unset ($files, $v);


$_path = $_GET['_path']; //Dari .htaccess
$reqMethod = strtolower($_SERVER['REQUEST_METHOD']);
$akhirPath = substr($_path, -1);

if ($_path === '' || $akhirPath == '/') $_path .= 'index';
define("APP_PATH", $_path);
$_path = "app/$_path";
$filename = ($reqMethod === 'get') ? "$_path.php" : "$_path.$reqMethod.php";

$_fileFound = file_exists($filename);
while (!$_fileFound) {
  $_path = dirname($_path);
  if ($_path === '.') break;
  $filename = ($reqMethod === 'get') ? "$_path.php" : "$_path.$reqMethod.php";
  $_fileFound = file_exists($filename);
}
define('PATH_PARAMS', substr(APP_PATH, strlen($_path)-4, 500) );

//404 handling
//If not GET, just show JSONResponse.
if (!$_fileFound && $reqMethod !== 'get') JSONResponse::Error('404 Service not found');
//for GET services:
if (!$_fileFound) $_path = "app/".APP_PATH; //Untuk loop cari 404.php terdalam
while (!$_fileFound) { //Cari 404.php terdalam
  $_path = dirname($_path);
  if ($_path === '.') break;
  $filename = "$_path/404.php";
  $_fileFound = file_exists($filename);
}
if (!$_fileFound) echo "
<h1>404 Not found.</h1>
<p>Not even app/404.php is found</p>";

//Untuk 403, 500, dihandle oleh apache

unset ($reqMethod, $akhirPath, $origPath, $_path404);
if ($_fileFound) { include $filename; }