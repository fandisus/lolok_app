<?php
$title = '403 '.APPNAME;

include DIR.'/app/templates/sidebar_layout.php';

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
      <h1 id="kode">403</h1>
      <?php if (isset($GLOBALS['login'])) { ?>
        <h2 id="keterangan">Sorry, you might need to login as another user to access this page.</h2>
        <div>Try changing user access or select different menu.</div>
        <div>Or click this link to <a href="/login">logout</a></div>
      <?php } else { ?>
        <h2 id="keterangan">Sorry, you need to login to access this page.</h2>
        Click this link to <a href="/login">login</a>
      <?php } ?>
    </div>
<?php }