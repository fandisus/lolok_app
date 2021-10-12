<?php
//$_topMenu = '';
// include DIR.'/engine/templates/topmenu_layout.php';

use Fandisus\Lolok\DB;
use Fandisus\Lolok\Debug;
DBConnect('oracle');
$menus = DB::get('SELECT * FROM menus');
Debug::print_r($_SERVER);  

function htmlHead() {}
function bodyEnd() {}
function mainContent() {
}