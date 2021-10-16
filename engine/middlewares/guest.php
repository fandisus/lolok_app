<?php
namespace Fandisus\Lolok;

class TopMenu {
  public $showLogo = true; //bool
  public $barColor = 'blue'; //string
  public $menus; //array
  public $leftLogo; //?string
  public $rightLogo; //?string
  public function __construct(bool $showLogo, string $barColor='blue', array $menus=[]) {
    $this->showLogo = $showLogo;
    $this->barColor = $barColor;
    $this->menus = $menus;
  }
}
class MenuItem {
  public $href; //string
  public $text; //string
  public $icon; //string
  public function __construct(string $href, string $text, string $icon = '') {
    $this->$href = $href; $this->text = $text; $this->icon = $icon;
  }
}

$_topMenu = new TopMenu(false, 'blue', [
  new MenuItem('/about', 'About')
]);
$_topMenu->leftLogo = WEBHOME.LOGO_IMAGE;

?>