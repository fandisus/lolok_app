<?php
//- Only appear for laptop and tablets (larger screen)
@render($_topMenu);

function renderFomanticMenuItem($menuItem, $level=0) {
  if (count($menuItem->subMenus) === 0) {
    ?><a class="item" href="<?= WEBHOME.$menuItem->href ?>"><?= $menuItem->text ?></a><?php
  } else {
    $class = ($level > 0) ? "item" : "ui dropdown item"
    ?>
    <div class="<?= $class ?>" level="<?=$level?>">
      <?= $menuItem->text ?>
      <i class="dropdown icon"></i>
      <div class="menu">
        <?php foreach ($menuItem->subMenus as $sub) renderFomanticMenuItem($sub, ++$level); ?>
      </div>
    </div><?php
  }
}

function render($topMenu) {
  //- topMenu: { showLogo:, leftLogo:, rightLogo:, barColor:, menus:[]}
  //- barcolor is semantic-ui color class
  if (!isset($topMenu)) {
    ?><script>alert('Topmenunav need "$_topMenu" variable defined');</script><?php 
    return;
  }


  $colorCodes = (object) ['red'=>'#db2828', 'blue'=>'#2185D0','teal'=>'#00B5AD'];
  ?>
  <div class="tablet computer only">
    <header class="ui top computer">
      <?php if ($topMenu->leftLogo !== '') { ?>
        <div class="ui container">
          <div id="logoheader">
            <?php if ($topMenu->leftLogo !== '') { ?>
              <img class="ui middle aligned image" src="<?= $topMenu->leftLogo ?>" width="80px" />
            <?php } ?>
            <?php if ($topMenu->rightLogo !== '') { ?>
              <img class="ui middle aligned right floated image" src="<?= $topMenu->rightLogo ?>" />
            <?php } ?>
            <div style="clear: both;"></div>
          </div>
        </div>
      <?php } ?>

      <div id="menu-strip">
        <div class="ui sticky" style="z-index: 801; background:<?= $colorCodes->{$topMenu->barColor} ?>;">
          <div class="ui container">
            <div class="ui inverted menu <?= $topMenu->barColor ?>">
              <a class="item" href="<?= WEBHOME ?>"><i class="home icon"></i></a>
              <?php foreach ($topMenu->menus as $menu) renderFomanticMenuItem($menu); ?>
              <a class="right item" href="<?=WEBHOME.'login'?>">Login</a>
            </div>
          </div>
        </div>
      </div>
    </header>
  </div>
<?php }
