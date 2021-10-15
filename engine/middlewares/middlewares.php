<?php
function routeMatch($routePattern) {
  $routeEnding = substr($routePattern, -1);
  if ($routeEnding === '*') {
    $route = substr($routePattern, 0, -1);
    if ($route === substr(APP_PATH, 0, strlen($route)) ) return true;
  } else {
    $filePath = substr(APP_PATH, 0, -strlen(PATH_PARAMS));
    if ($routePattern === $filePath) return true;;
  }
}

$config = json_decode(file_get_contents(DIR."/engine/config.json"));
foreach ($config->middlewares as $name=>$m) {
  if (!isset($m->route)) die("route for $name is not defined");
  if (!isset($m->middleware)) die("middleware for $name is not defined");
  if (!file_exists(__DIR__."/$m->middleware")) die("middleware for $name does not exist");
  if (routeMatch($m->route) && (!isset($m->except) || !routeMatch($m->except))) include "$m->middleware";
}
