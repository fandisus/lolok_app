<?php

use LolokApp\Helper\Session;

Session::$login->logout();
header('location:'.WEBHOME.'login');