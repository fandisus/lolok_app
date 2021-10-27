<?php

use Fandisus\Lolok\JSONResponse;
use LolokApp\AccessProfile;

if (!function_exists($_POST['a'])) JSONResponse::Error("Service {$_POST['a']} not available yet");
else $_POST['a']();

function getData() {
  if (!$GLOBALS['login']->canAccess(APP_PATH, 'read')) JSONResponse::Error('Access denied');
  $result = AccessProfile::all();
  JSONResponse::Success(['profiles'=>$result]);
}

function init() {
  if (!$GLOBALS['login']->canAccess(APP_PATH, 'read')) JSONResponse::Error('Access denied');
  $availableMenus = AccessProfile::availableMenus();
  JSONResponse::Success(['availableMenus'=>$availableMenus]);
}

function save() {
  $obj = json_decode($_POST['obj']);
  if (trim($obj->name) === '') JSONResponse::Error('Profile name is required.');
  // if (count($obj->menu_tree) === 0) JSONResponse::Error('No menu access');
  try {
    if ($obj->pk === '') { //new
      if (!$GLOBALS['login']->canAccess(APP_PATH, 'create')) JSONResponse::Error('Access denied');
      $accesProfile = new AccessProfile($obj);
      $accesProfile->insert();
    } else { //update
      if (!$GLOBALS['login']->canAccess(APP_PATH, 'update')) JSONResponse::Error('Access denied');
      $accessProfile = AccessProfile::find(['name'=>$obj->pk]);
      if ($accessProfile === null) JSONResponse::Error('Profile not found');
      // JSONResponse::Debug(print_r($accessProfile, true));
      $accessProfile->name = $obj->name;
      $accessProfile->menu_tree = $obj->menu_tree;
      $accessProfile->update();
    }
    JSONResponse::Success();  
  } catch (\Exception $ex) {
    JSONResponse::Error($ex->getMessage());
  }
}

function remove() {
  if (!$GLOBALS['login']->canAccess(APP_PATH, 'delete')) JSONResponse::Error('Access denied');
  $name = $_POST['name'];
  $accessProfile = AccessProfile::find(['name'=>$name]);
  if ($accessProfile === null) JSONResponse::Error('Profile not found');
  try {
    $accessProfile->delete();
    JSONResponse::Success();
  } catch (\Exception $ex) { JSONResponse::Error($ex->getMessage()); }
}