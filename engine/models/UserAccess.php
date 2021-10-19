<?php
namespace LolokApp;
use Fandisus\Lolok\Model;

class UserAccess extends Model {
  protected static function tableName() { return 'user_accesses'; }
  protected static function PK() { return ['uid']; }
  protected static function hasSerial() { return false; }
  protected static function jsonColumns() { return []; }

  public $uid, $profile;
  private $_menuTree, $_accesses;

  public static function availableMenus() {
    return json_decode(json_encode([
      ['text'=>'User Management', 'icon'=>'users', 'href'=>'', 'subMenus'=>[
        ['text'=>'Users', 'icon'=>'users', 'href'=>'user/users',
          'rights'=>['read', 'create', 'update', 'delete', 'changePass'], 'subMenus'=>[]
        ],
        ['text'=>'Access Profile', 'icon'=>'user lock', 'href'=>'user/access-profile',
          'rights'=>['read', 'create', 'update', 'delete'], 'subMenus'=>[],
        ]
      ],], //User Management
    ]));
  }

  public function __construct($props) {
    parent::__construct($props);
    $this->load();
  }

  public function load() {
    $this->_menuTree = $this->loadMenuTree($this->profile);
    $this->_accesses = $this->getAccessFromTree();
  }
  public function getMenuTree() { return $this->_menuTree; }
  public function getAccesses() { return $this->_accesses; }

  private function loadMenuTree($profile) {
    if ($profile === null) return null;
    $filename = DIR."/engine/settings/$profile.json";
    if (!file_exists($filename)) return null;
    return json_decode(file_get_contents($filename));
  }
  private function getAccessFromTree() { //Flatten the menuTree, for checking urls and accesses
    if ($this->_menuTree === null) return [];
    $result = json_decode(json_encode($this->menuTree));
    $result = $result->subMenus;
    $hasChild = true;
    while (!$hasChild) {
      $hasChild = false;
      $count = count($result);
      for ($i=0; $i<$count; $i++) {
        foreach ($result[$i]->subMenus as $v) {
          $result[] = $v; //Put submenus in root
          $hasChild = true;
        }
        $result[$i]->subMenus=[];
      }
    }
    return $result;
  }

  public function saveMenuTree($profile) { //Save to json file, at engine/settings
    if ($profile === '_available') throw new \Exception('Cannot use _available as profile name.');
    if (preg_match('/[^W]+/', $profile)) throw new \Exception('Invalid profile name.');;
    $filename = DIR."/engine/settings/$profile.json";
    file_put_contents($filename, json_encode($this->_menuTree));
  }

}