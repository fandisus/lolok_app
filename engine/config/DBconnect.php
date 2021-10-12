<?php
use Fandisus\Lolok\DB;
function DBConnect($connectionName) {
  $config = json_decode(file_get_contents(DIR."/engine/config.json"));
  if (!isset($config->DBConnections->$connectionName)) throw new \Exception('DB Connection not found. Please check engine/config.json');
  DB::setConnectionWithObject($config->DBConnections->$connectionName);
}