<?php

use LolokApp\Helper\Session;

$title = '403 '.APPNAME;

include DIR.'/app/templates/sidebar/layout.php';

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
    #center-container h2 { margin:0; padding:0;}
  </style>
<?php }

function mainContent() { ?>
    <div id="center-container">
      <div><em data-emoji=":person_gesturing_no:" class="big"></em></div>
      <h1 id="kode">403</h1>
      <?php if (isset(Session::$login)) { ?>
        <h2 id="keterangan">Sorry, you might need to login as another user to access this page.</h2>
        <div>Try changing user access or select different menu.</div>
        <div>Or click this link to <a href="/login">logout</a></div>
      <?php } else { ?>
        <h2 id="keterangan">Sorry, you need to login to access this page.</h2>
        Click this link to <a href="/login">login</a>
      <?php } ?>
    </div>
<?php }