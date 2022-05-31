<?php

use Fandisus\Lolok\Debug;

include DIR.'/app/templates/_fomantic_menu.php';

$menus = (isset($GLOBALS['menus'])) ? $GLOBALS['menus'] : [];
$colorCodes = $GLOBALS['colorCodes'];
$header = (object) [
  'logo'=>'/images/dot.png',
  'smallLogo'=>'/images/dot.png',
  'barColor'=>'blue',
  'menus'=>$menus,
  'username'=>$session->user->username,
  'availableAccesses'=>$session->available_accesses,
  'currentAccess'=>$session->currentAccess->name
];

?>
<style>
  .ui.vertical.menu { margin: 0;}
  /* For sidebar menus and desktop side menu */
  .ui.vertical.menu .item > i.icon.left { float: none; margin: 0em 0.35714em 0em 0em; }
  .ui.left.vertical.accordion.menu .item { font-size: 1.15em;}
  .ui.left.vertical.accordion.menu .item .content .menu .item { font-size: 0.95em; margin: 1em 0 1em 8px; padding:0}

  #mtopnav { grid-area: mtopnav; }
  #topnav { grid-area: topnav; }
  #sidenav { grid-area: sidenav; }
  #maincontainer { grid-area: maincontainer; }
  #pusher {
    background-color: ghostwhite; height:100vh; max-height: 100vh;
    display: grid; gap: 0px 10px;
    grid-template:
    "mtopnav mtopnav" auto
    "topnav topnav" auto
    "sidenav maincontainer" 1fr
    / auto 1fr;
  }
</style>

<div class="ui left sidebar inverted vertical accordion <?= $header->barColor ?> menu" id="mobilemenus">
  <div class="item"><img src="<?= $header->logo ?>" style="margin: 0 auto"/></div>
  <?php foreach ($header->menus as $menu) renderFomanticSideMenuItem($menu); ?>
</div>

<!-- sidebar mobile accesses -->
<div class="ui left sidebar inverted vertical accordion <?= $header->barColor ?> menu" id="mobileaccesses">
  <div class="item">
    <center><i class="key large icon"></i></center>
  </div>
  <?php foreach ($header->availableAccesses as $a) { ?>
  <a class="item" href="<?= WEBHOME.'user/changeAccess?id='.$a->id ?>"><?= $a->name ?></a>
  <?php } ?>
</div>

<!-- sidebar mobile logout -->
<div class="ui left sidebar inverted vertical accordion <?= $header->barColor ?> menu" id="mobileprofile">
  <div class="item">
    <center><i class="user large icon"></i></center>
  </div>
  <a class="item" href="/user/logout"><i class="power icon"></i> Logout</a>
</div>


<div class="pusher" id="pusher">
  <!-- topnav mobile -->
  <div class="mobile only" id="mtopnav">
    <div class="ui top fixed inverted blue menu">
      <a class="launch icon item" id="m-menus-toggle"><i class="content icon"></i></a>
      <a id="m-logo" class="item" href="/user">
        <img src="<?= $header->smallLogo ?>" style="width:auto; height:25px;"/>
      </a>
      <div class="right menu">
        <a class="item" href="#" id="m-accesses-toggle"><i class="key icon"></i></a>
        <a class="item" href="#" id="m-profile-toggle"><i class="user icon"></i></a>
      </div>
    </div>
    <div class="ui top inverted blue menu" style="margin:0">
      <a class="item" href="#">Dummy to keep space</a>
    </div>
  </div>

  <!-- topnav desktop -->
  <div class="tablet computer only" id="topnav">
    <header class="ui top">
      <div id="menu-strip">
        <div class="ui sticky" style="z-index: 801; background:<?= $colorCodes->{$header->barColor} ?>;">
            <div class="ui inverted menu <?= $header->barColor ?>">
              <a id="m-logo" class="item" href="/user"><img src="<?=$header->smallLogo?>" style="width:auto; height:25px;"/></a>
              <div class="right menu">
                <div class="ui dropdown item" tabindex="0">
                  <i class="key icon"></i><?= $header->currentAccess ?>
                  <div class="menu left transition hidden" tabindex="-1">
                    <?php foreach ($header->availableAccesses as $a) { ?>
                    <a class="item" href="<?= WEBHOME.'user/changeAccess?id='.$a->id ?>"><?= $a->name ?></a>
                    <?php } ?>
                  </div>
                </div>
                <div class="ui dropdown item" tabindex="0">
                  <i class="user icon"></i> <?= $header->username ?>
                  <div class="menu left transition hidden" tabindex="-1">
                    <a class="item" href="/user/logout"><i class="power icon"></i> Logout</a>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </header>
  </div>

  <!-- sidemenu desktop -->
  <style>
    #sidenav { position:relative; background: url('/images/sidebar-1.jpg') no-repeat; overflow:auto; width: 250px; transition: width 0.3s; }
    #sidenavcollapse { position:absolute; top:10px; left:8px; z-index:10; cursor:pointer; }
    #sidenav > .menu { width:100%; min-height:100%; background:rgba(240,248,255,0.65); }
  </style>
  <div class="computer only" id="sidenav">
    <h2 id="sidenavcollapse"><i class="chevron circle left icon"></i></h2>
    <div class="ui left vertical accordion menu">
      <div class="item">&nbsp;</div>
      <?php foreach ($header->menus as $menu) renderFomanticSideMenuItem($menu); ?>
    </div>
  </div>


  <!-- mainContent -->
  <div style="overflow:auto" id="maincontainer">
  <?php if (function_exists('mainContent')) bodyEnd(); ?>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#m-menus-toggle').click(() => { $('#mobilemenus').sidebar('toggle'); });
  $('#m-accesses-toggle').click(() => { $('#mobileaccesses').sidebar('toggle'); });
  $('#m-profile-toggle').click(() => { $('#mobileprofile').sidebar('toggle'); });
  $('#sidenavcollapse').click(()=>{
    let curWidth = $('#sidenav').css('width');
    if (curWidth === '250px') {
      $('#sidenav').css('width', '50px');
      $('#sidenavcollapse .chevron').removeClass('left');
      $('#sidenavcollapse .chevron').addClass('right');
      $('#sidenav .menu').fadeOut();
    } else {
      $('#sidenav').css('width', '250px');
      $('#sidenavcollapse .chevron').removeClass('right');
      $('#sidenavcollapse .chevron').addClass('left');
      $('#sidenav .menu').fadeIn();
    }
  });
});
</script>