<?php

use Fandisus\Lolok\DB;
use Fandisus\Lolok\JSONResponse;
use LolokApp\Access;
use LolokApp\AccessPage;
use LolokApp\Helper\Session;

if (!function_exists($_POST['a'])) JSONResponse::Error("Service {$_POST['a']} not available yet");
else $_POST['a']();

function init() {
  $availableMenus = Access::availablePages();
  $accesses = Access::all();
  foreach ($accesses as &$a) $a->loadPages();
  JSONResponse::Success(['availableMenus'=>$availableMenus, 'accesses'=>$accesses]);
}

function getData() {
  $accesses = Access::all();
  foreach ($accesses as &$a) $a->loadPages();
  JSONResponse::Success(['accesses'=>$accesses]);
}

function save() {
  if (!Session::$currentAccess->canAccess(APP_PATH, 'save')) JSONResponse::Error('Access denied');

  $obj = json_decode($_POST['obj']);
  if (trim($obj->name) === '') JSONResponse::Error('Access name is required.');
  if (count($obj->menu_tree) === 0) JSONResponse::Error('Please select at least one Page to access');
  $obj->name = trim($obj->name);
  $nameCheck = Access::findWhere('WHERE name=:NAME', '*', ['NAME'=>$obj->name]);
  if ($nameCheck) JSONResponse::Error('Access name already exists');
  try {
    $accessProfile = Access::loadDbRow($obj);
    $accessProfile->insert();
    $newId = $accessProfile->id;
    foreach ($obj->menu_tree as $menu) {
      $accessPage = new AccessPage(['access_fk'=>$newId, 'url'=>$menu->url, 'rights'=>$menu->rights]);
      $accessPage->insert();
    }
    $accessProfile->loadPages();
    JSONResponse::Success(['newAccess'=>$accessProfile]);  
  } catch (\Exception $ex) {
    JSONResponse::Error($ex->getMessage());
  }
}
function update() {
  if (!Session::$currentAccess->canAccess(APP_PATH, 'update')) JSONResponse::Error('Access denied');
  $obj = json_decode($_POST['obj']);
  if (trim($obj->name) === '') JSONResponse::Error('Access name is required.');
  if (count($obj->menu_tree) === 0) JSONResponse::Error('Please select at least one Page to access');
  $obj->name = trim($obj->name);
  $nameCheck = Access::findWhere('WHERE name=:NAME AND name<>:PK', '*', ['NAME'=>$obj->name, 'PK'=>$obj->pk]);
  if ($nameCheck) JSONResponse::Error('Access name already exists');
  try {
    $accessProfile = Access::findWhere('WHERE name=:NAME', '*', ['NAME'=>$obj->pk]);
    if ($accessProfile === null) JSONResponse::Error('Access profile not found');
    if ($accessProfile->name !== $obj->name || $accessProfile->role !== $obj->role) {
      $accessProfile->name = $obj->name;
      $accessProfile->update();
    }
    DB::exec('DELETE FROM access_pages WHERE access_fk=:AID',['AID'=>$accessProfile->id]);
    foreach ($obj->menu_tree as $menu) {
      $accessPage = new AccessPage(['access_fk'=>$accessProfile->id, 'url'=>$menu->url, 'rights'=>$menu->rights]);
      $accessPage->insert();
    }

    $accessProfile->loadPages();
    JSONResponse::Success(['newAccess'=>$accessProfile]);  
  } catch (\Exception $ex) {
    JSONResponse::Error($ex->getMessage());
  }

}

function delete() {
  if (!Session::$currentAccess->canAccess(APP_PATH, 'delete')) JSONResponse::Error('Access denied');
  $name = $_POST['name'];
  $accessProfile = Access::findWhere('WHERE name=:NAME', '*', ['NAME'=>$name]);
  if ($accessProfile === null) JSONResponse::Error('Profile not found');
  try {
    $accessProfile->delete();
    JSONResponse::Success();
  } catch (\Exception $ex) { JSONResponse::Error($ex->getMessage()); }
}