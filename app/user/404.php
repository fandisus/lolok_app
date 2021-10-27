<?php
define('APP_PATH', $_GET['_path']);
define('PATH_PARAMS', '');

$title = '404 '.APPNAME;

include DIR.'/engine/middlewares/allMiddleware.php';
include DIR.'/engine/middlewares/userMiddleware.php';
include DIR.'/engine/templates/sidebar_layout.php';

function htmlHead() { ?>
  <style>
    #center-container {
      margin: 0 auto;
      display:block;
      text-align: center;
      margin-top: 20vh;
    }
    #kode {
      margin: 0; padding: 0; display:inline-block; height: 140px; width: 300px; 
      border: 5px solid white; border-radius: 10px;
      font-size:7em;
    }
  </style>
<?php }

function mainContent() { ?>
    <div id="center-container">
      <h1 id="kode">404</h1>
      <h2 id="keterangan">Sorry, the page you are looing for does not exist.</h2>
    </div>
<?php }