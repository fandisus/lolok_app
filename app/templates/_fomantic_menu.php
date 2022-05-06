<?php
$GLOBALS['colorCodes'] = (object) [
  'red'=>'#db2828', 'orange'=>'#F2711C','yellow'=>'#FBBD08', 'olive'=>'#AAC00F', 'green'=>'#21BA45',
  'teal'=>'#00B5AD', 'blue'=>'#2185D0', 'violet'=>'#6435C9', 'purple'=>'#A333C8', 'pink'=>'#E03997',
  'brown'=>'#A5673F', 'grey'=>'#767676'
];

function renderFomanticMenuItem($menuItem, $level=0) {
  if (count($menuItem->subMenus) === 0) {
    ?><a class="item" href="/<?= $menuItem->href ?>"><?= $menuItem->text ?></a><?php
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

function renderFomanticSideMenuItem($menuItem, $level=0) {
  if (count($menuItem->subMenus) === 0) {
    ?><a class="item" href="/<?= $menuItem->href ?>">
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
          <?php foreach ($menuItem->subMenus as $sub) renderFomanticSideMenuItem($sub, ++$level); ?>
        </div>
      </div>
    </div><?php
  }
}
