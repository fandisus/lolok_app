<?php
define("DIR", dirname(dirname(__DIR__)));
define("DS", DIRECTORY_SEPARATOR);
$webhome = ($_SERVER['SCRIPT_NAME'] === '/index.php') ? '/' : dirname($_SERVER['SCRIPT_NAME']).'/';
define("WEBHOME",$webhome);

loadConstants();
function loadConstants() {
  $_config = json_decode(file_get_contents(DIR."/engine/config.json"));
  foreach ($_config->constants as $k=>$v) define($k, $v);  
}
