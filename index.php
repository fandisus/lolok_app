<?php
require 'vendor/autoload.php';

use Fandisus\Lolok\Files;
use Fandisus\Lolok\JSONResponse;

loadStaticFiles();
function loadStaticFiles() {
  //Possible improvement: use fopen, fseek and fpassthru to enable partial download.
  $config = json_decode( file_get_contents('engine/config.json') );
  foreach ($config->publicFolders as $route) {
    $filename = $route->path.$_GET['_path'];
    if (file_exists($filename) && !is_dir($filename)) {
      if (isset($route->middleware)) {
        $middlePath = 'engine/middlewares/'.$route->middleware;
        if (file_exists($middlePath)) include $middlePath;
      }
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $filename);
      finfo_close($finfo);

      header('Content-Type: '.$mime);
      header('Content-Length: ' . filesize($filename));

      readfile($filename);
      exit;
    }
  }
}


$fileSearch = getFilename($_GET['_path']); //$_GET['path'] is from .htaccess
function getFilename(string $path): object {
  $result = new \stdClass();
  $reqMethod = strtolower($_SERVER['REQUEST_METHOD']);
  $result->reqMethod = $reqMethod;
  $akhirPath = substr($path, -1);
  
  if ($path === '' || $akhirPath == '/') $path .= 'index';
  $result->APP_PATH = $path;
  $path = "app/$path";
  $filename = ($reqMethod === 'get') ? "$path.php" : "$path.$reqMethod.php";
  
  $fileFound = file_exists($filename);
  while (!$fileFound) {
    $path = dirname($path);
    if ($path === '.') break;
    $filename = ($reqMethod === 'get') ? "$path.php" : "$path.$reqMethod.php";
    $fileFound = file_exists($filename);
  }
  $result->PATH_PARAMS = substr($result->APP_PATH, strlen($path)-4, 500);
  $result->found = $fileFound;
  $result->filename = ($fileFound) ? $filename : '';
  return $result;
}

//After getting APP_PATH and PATH_PARAMS, load the other constants
$files = Files::GetDirFiles(__DIR__.'/engine/config');
foreach ($files as $v) include($v);

//After getting APPNAMESPACE from engine/config/constants.php --> config.json, autoload models:
include DIR."/engine/models/autoload.php";
unset ($files, $v);

if ($fileSearch->found) {
  define('APP_PATH', $fileSearch->APP_PATH);
  define('PATH_PARAMS', $fileSearch->PATH_PARAMS);
  $filename = $fileSearch->filename;
  unset ($fileSearch);
  include DIR.'/engine/middlewares/middlewares.php';
  include $filename;
  die;
}

//404 handling
//If not GET, just show JSONResponse.
if (!$fileSearch->found) handle404($fileSearch);
function handle404($fileSearch) {
  if ($fileSearch->reqMethod !== 'get') JSONResponse::Error('404 Service not found');
  //for GET services:
  $fileFound = false;
  $_path = "app/$fileSearch->APP_PATH"; //Untuk loop cari 404.php terdalam
  while (!$fileFound) { //Cari 404.php terdalam
    $_path = dirname($_path);
    if ($_path === '.') break;
    $filename = "$_path/404.php";
    $fileFound = file_exists($filename);
  }
  if (!$fileFound) echo "
  <h1>404 Not found.</h1>
  <p>Not even app/404.php is found</p>";
  
  //Untuk 403, 500, dihandle oleh apache
  if ($fileFound) include $filename;
}
