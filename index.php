<?php
require 'vendor/autoload.php';

use Fandisus\Lolok\Debug;
use Fandisus\Lolok\Files;
use Fandisus\Lolok\JSONResponse;

$files = Files::GetDirFiles(__DIR__.'/engine/config');
foreach ($files as $v) include($v);

//After getting APPNAMESPACE from engine/config/constants.php --> config.json, autoload models:
include DIR."/engine/models/autoload.php";

Debug::print_r($files);
die;

unset ($files, $v);



$_path = $_GET['path']; //Dari .htaccess
$reqMethod = strtolower($_SERVER['REQUEST_METHOD']);
$akhirPath = substr($_path, -1);

if ($_path === '' || $akhirPath == '/') $_path .= 'index';
$_path = ($reqMethod === 'get') ? "app/$_path.php" : "app/$_path.$reqMethod.php";
$origPath = $_path;

$_fileFound = file_exists($_path);
while (!$_fileFound) {
  $_path = dirname($_path);
  if ($_path === 'app') break;
  $_path = ($reqMethod === 'get') ? "$_path.php" : "$_path.$reqMethod.php";
  $_fileFound = file_exists($_path);
}

//If not GET, just show JSONResponse.
JSONResponse::Error('404 Service not found');

//404 handling
if (!$_fileFound) $_path = $origPath; //Untuk loop cari 404.php terdalam
while (!$_fileFound) { //Cari 404.php terdalam
  $_path = dirname($_path);
  if ($_path === '.') break;
  $_path404 = "$_path/404.php";
  $_fileFound = file_exists($_path404);
  if ($_fileFound) $_path = $_path404;
}
if (!$_fileFound) echo "
<h1>404 Not found.</h1>
<p>Not even app/404.php is found</p>";

//Untuk 403, 500, dihandle oleh apache

unset ($reqMethod, $akhirPath, $origPath, $_path404);
if ($_fileFound) include $_path;