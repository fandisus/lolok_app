<?php
namespace LolokApp;

use Fandisus\Lolok\Model;

/*
This class represents an Access Profile.
Represents a profile of menu structure, and access rights.
*/
class AccessProfile extends Model {
  protected static function tableName() { return 'access_profile'; }
  protected static function PK() { return ['name']; }
  protected static function hasSerial() { return false; }
  protected static function jsonColumns() { return ['menu_tree']; }
  public $name;
  public $menu_tree;
  private $_rights;

  public static function availableMenus() {
    return json_decode(json_encode([
      ['name'=>'user_management', 'text'=>'User Management', 'icon'=>'users', 'href'=>'', 'subMenus'=>[
        ['name'=>'users', 'text'=>'Users', 'icon'=>'users', 'href'=>'user/users',
          'rights'=>['read', 'create', 'update', 'delete', 'changePass'], 'subMenus'=>[]
        ], //Users
        ['name'=>'access_profile','text'=>'Access Profile', 'icon'=>'user lock', 'href'=>'user/access-profile',
          'rights'=>['read', 'create', 'update', 'delete'], 'subMenus'=>[],
        ]
      ],], //User Management
    ]));
  }

  private function getRightsFromTree() { //Flatten the menuTree, for checking urls and rights
    if ($this->menu_tree === null) return [];
    $result = json_decode(json_encode($this->menu_tree));
    $hasChild = true;
    while ($hasChild) {
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
    $this->_rights = $result;
    return $result;
  }
  public function getRights() {
    if ($this->_rights === null) return $this->getRightsFromTree();
    return $this->_rights;
  }
}