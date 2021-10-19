<?php
//- Only appear for laptop and tablets (larger screen)

use Fandisus\Lolok\Debug;

@render($_sidebarMenus, $_sidebarHomeLogo);
function renderFomanticMenuItem($menuItem, $level=0) {
  // Debug::print_r($menuItem->subMenus); die;
  if (count($menuItem->subMenus) === 0) {
    ?><a class="item" href="<?= WEBHOME.$menuItem->href ?>">
      <?php if ($menuItem->icon) echo "<i class=\"left $menuItem->icon icon\"></i>"; ?>
      <?= $menuItem->text ?>
    </a><?php
  } else { ?>
    <div class="item" level="<?=$level?>">
      <div class="title">
        <?php if ($menuItem->icon) echo "<i class=\"left $menuItem->icon icon\"></i>"; ?>
        <?=$menuItem->text?>
        <i class="dropdown icon"></i>
      </div>
      <div class="content">
        <div class="menu">
          <?php foreach ($menuItem->subMenus as $sub) renderFomanticMenuItem($sub, ++$level); ?>
        </div>
      </div>
    </div><?php
  }
}

function render($sidebarMenus, $sidebarHomeLogo) {
  //- topMenu: { showLogo:, leftLogo:, rightLogo:, barColor:, menus:[]}
  //- barcolor is semantic-ui color class
  if (!isset($sidebarMenus)) {
    ?><script>alert('Sidebarnav need "sidebarmenu" defined');</script><?php 
    return;
  }
  ?>
  <style>
    #sidebarnav.ui.grid>* { padding:0; }
    #sidebarnav.ui.grid { margin: 0; }
    .ui.vertical.menu .item > i.icon.left { float: none; margin: 0em 0.35714em 0em 0em; } /* Put menu icon on left */
    .ui.accordion.menu .item .title>.dropdown.icon { margin: 0} /* Override semantic UI default css, so that menu dropdown icon looks good */
  </style>
  <div class="ui grid computer only tablet only" id="sidebarnav">
    <nav class="ui inverted blue sidebar visible vertical accordion borderless menu" style="box-shadow: 1px 1px 2px 0 rgba(34,36,38,1);">
      <?php if (isset($sidebarHomeLogo)) { ?>
        <div class="item" style="background: white;">
          <a class="ui logo icon image" href="<?= WEBHOME ?>user">
            <img src="<?= WEBHOME.$sidebarHomeLogo ?>"/>
          </a>
        </div>
      <?php } else { ?>
        <div class="item" style="background:white;">
          <a class="ui logo icon image" href="<?= WEBHOME ?>user"><i class="ui home icon massive"></i></a>
        </div>
      <?php } ?>

      <?php foreach ($sidebarMenus as $m) renderFomanticMenuItem($m); ?>

      <a class="item" href="<?= WEBHOME ?>user/change-password">
        <i class="privacy icon left"></i> Change password
      </a>
      <a class="item" href="<?= WEBHOME ?>user/logout">
        <i class="power icon left"></i> Logout
      </a>
    </nav>
  </div>
<?php }
