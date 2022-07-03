<?php
namespace LolokApp;

use Fandisus\Lolok\DB;
use Fandisus\Lolok\Debug;
use Fandisus\Lolok\Model;

/*
This class represents an Access Profile.
Represents a profile of menu structure, and access rights.
*/
class Access extends Model {
  protected static function tableName() { return 'accesses'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }
  public $id, $name, $role, $created_at, $updated_at;
  private $_pages;

  public static function load($access_pk) {
    $rows = DB::get('SELECT * FROM accesses a LEFT JOIN access_pages ap ON a.id = ap.access_fk WHERE a.id=:ID',['ID'=>$access_pk]);
    if (count($rows) === 0) throw new \Exception('Failed to load user access');
    $access = new self($rows[0]);
    $pages = array();
    foreach ($rows as $r) $pages[] = new AccessPage($r);
    $access->_pages = $pages;
    return $access;
  }

  public static function ofUser($user_fk) {
    $rows = DB::get('SELECT a.* FROM accesses a LEFT JOIN user_accesses ua ON a.id = ua.access_fk WHERE ua.user_fk = :UID',['UID'=>$user_fk]);
    $result = array_map(function($r) { return new self($r); }, $rows);
    return $result;
  }

  public static function availablePages() {
    return json_decode(json_encode([
      ['text'=>'User Management', 'icon'=>'users', 'href'=>'', 'subMenus'=>[
        ['text'=>'Users', 'icon'=>'users', 'href'=>'user/users',
          'rights'=>['read', 'create', 'update', 'delete', 'changePass'], 'subMenus'=>[]
        ], //Users
        ['text'=>'Access Profile', 'icon'=>'user lock', 'href'=>'user/access-profile',
          'rights'=>['read', 'create', 'update', 'delete'], 'subMenus'=>[],
        ]
      ],], //User Management
    ]));
  }

  public function getMenus() {
    if ($this->role === 'admin') return self::availablePages();
    return self::availablePages();
  }
  // private function getRightsFromTree() { //Flatten the menuTree, for checking urls and rights
  //   if ($this->menu_tree === null) return [];
  //   $result = json_decode(json_encode($this->menu_tree));
  //   $hasChild = true;
  //   while ($hasChild) {
  //     $hasChild = false;
  //     $count = count($result);
  //     for ($i=0; $i<$count; $i++) {
  //       foreach ($result[$i]->subMenus as $v) {
  //         $result[] = $v; //Put submenus in root
  //         $hasChild = true;
  //       }
  //       $result[$i]->subMenus=[];
  //     }
  //   }
  //   $this->_rights = $result;
  //   return $result;
  // }
  // public function getRights() {
  //   if ($this->_rights === null) return $this->getRightsFromTree();
  //   return $this->_rights;
  // }


  // public function getMenuTree() {
  //   if (!$this->loadAccess()) return null;
  //   return $this->_accessProfile->menu_tree;
  // }
  // public function getRights() {
  //   if (!$this->loadAccess()) return null;
  //   return $this->_accessProfile->getRights();
  // }
  // public function canAccess($href, $access='') {
  //   if ($this->username === 'admin') return true;

  //   $this->loadAccess();
  //   $ap = $this->_accessProfile;
  //   //Check href in accesses
  //   $filter = array_filter($ap->getRights(), function($a) use ($href) { return $a->href === $href; });
  //   if (count($filter) < 1) return false;
  //   //Check rights in access
  //   $menuItem = array_pop($filter);
  //   if (!isset($menuItem->rights) || !in_array($access, $menuItem->rights)) return false;
  //   return true;
  // }

}