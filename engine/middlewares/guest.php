<?php
namespace Fandisus\Lolok;

use Firebase\JWT\JWT;
use LolokApp\User;

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

if (isset($_COOKIE[JWT_NAME])) {
  try { $oUser = JWT::decode($_COOKIE[JWT_NAME], JWT_SECRET, ['HS256']); }
  catch (\Exception $ex) { setcookie(JWT_NAME, '', time()-3600); }

  $dbUser = User::find(['id'=>$oUser->id]);
  if ($dbUser === null) { setcookie(JWT_NAME, '', time()-3600); }
  elseif ($dbUser->jwt !== $_COOKIE[JWT_NAME]) $dbUser->logout();
  else $GLOBALS['login'] = $dbUser;
}

?>