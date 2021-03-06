<?php
use Fandisus\Lolok\DB;
function DBConnect($connectionName) {
  $config = json_decode(file_get_contents(DIR."/engine/config.json"));
  if (!isset($config->DBConnections->$connectionName)) throw new \Exception('DB Connection not found. Please check engine/config.json');
  DB::setConnectionWithObject($config->DBConnections->$connectionName);
}

$config = json_decode(file_get_contents(DIR."/engine/config.json"));
$arrDb = (array) $config->DBConnections;
@$dbConfig = array_shift($arrDb);
if ($dbConfig === null) { echo 'Failed to get database connection config'; die; }
else {
  try { DB::setConnectionWithObject($dbConfig); } catch (\Exception $ex) {
    echo 'Failed to connect to database<br />';
    if (DEBUG) echo 'Please check engine/config.json.<br />';
    echo $ex->getMessage(); die;
  }
}
