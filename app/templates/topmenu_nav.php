<?php
require_once('_fomantic_menu.php');
//- Only appear for laptop and tablets (larger screen)
$menus = (isset($GLOBALS['menus'])) ? $GLOBALS['menus'] : [];
$colorCodes = $GLOBALS['colorCodes'];
$header = (object) [
  'logo'=>'/images/dot.png',
  'rightLogo'=>'',
  'smallLogo'=>'/images/dot.png',
  'barColor'=>'blue',
  'menus'=>$menus
];

render($header);

function render($header) { global $colorCodes; ?>
  <style>
    .ui.vertical.menu .item > i.icon.left { float: none; margin: 0em 0.35714em 0em 0em; }
    #mobilesidebar.ui.vertical.sidebar.menu .item { font-size: 1.15em;}
    #mobilesidebar.ui.vertical.sidebar.menu .item .content .menu .item { font-size: 0.95em; margin: 1em 0 1em 8px; padding:0}
  </style>

  <div class="mobile only" id="mobilenav">
    <div class="ui top fixed menu mobile only">
      <a class="launch icon item" id="m-sidebar-toggle"><i class="content icon"></i></a>
      <a id="m-logo" class="item" href="/">
        <img src="<?= $header->smallLogo ?>" style="width:auto; height:25px;"/>
      </a>
    </div>
    <div class="ui left sidebar inverted vertical accordion <?= $header->barColor ?> menu" id="mobilesidebar">
      <div class="item"><img src="<?= $header->logo ?>" style="margin: 0 auto"/></div>
      <?php foreach ($header->menus as $menu) renderFomanticSideMenuItem($menu); ?>
      <a class="item" href="/login"><i class="left key icon"></i> Login</a>
    </div>
  </div>

  <div class="tablet computer only">
    <header class="ui top computer">
      <!-- Header Logos -->
      <?php if ($header->logo !== '') { ?>
        <div class="ui container">
          <div id="logoheader">
            <?php if ($header->logo !== '') { ?>
              <img class="ui middle aligned image" src="<?= $header->logo ?>" width="80px" />
            <?php } ?>
            <?php if ($header->rightLogo !== '') { ?>
              <img class="ui middle aligned right floated image" src="<?= $header->rightLogo ?>" />
            <?php } ?>
            <div style="clear: both;"></div>
          </div>
        </div>
      <?php } ?>

      <!-- Header Navigation -->
      <div id="menu-strip">
        <div class="ui sticky" style="z-index: 801; background:<?= $colorCodes->{$header->barColor} ?>;">
          <div class="ui container">
            <div class="ui inverted menu <?= $header->barColor ?>">
              <a class="item" href="/"><i class="home icon"></i></a>
              <?php foreach ($header->menus as $menu) renderFomanticMenuItem($menu); ?>
              <a class="right item" href="/login">Login</a>
            </div>
          </div>
        </div>
      </div>
    </header>
  </div>
<?php }
