<?php
define("DIR", dirname(dirname(__DIR__)));
define("DS", DIRECTORY_SEPARATOR);
define("WEBHOME",dirname($_SERVER['SCRIPT_NAME']).'/');

$_config = json_decode(file_get_contents(DIR."/engine/config.json"));
foreach ($_config->constants as $k=>$v) define($k, $v);
