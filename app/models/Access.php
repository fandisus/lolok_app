<?php
namespace LolokApp;

use Fandisus\Lolok\DB;
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
  public $_pages;
  public $_menuTree = [];

  public static function load($access_pk) {
    $row = DB::getOneRow('SELECT * FROM accesses a WHERE a.id=:ID',['ID'=>$access_pk]);
    if ($row === null) throw new \Exception('Failed to load user access');
    $access = self::loadDbRow($row);
    $access->loadPages();
    return $access;
  }

  public static function ofUser($user_fk) {
    $rows = DB::get('SELECT a.* FROM accesses a LEFT JOIN user_accesses ua ON a.id = ua.access_fk WHERE ua.user_fk = :UID',['UID'=>$user_fk]);
    $result = array_map(function($r) { return self::loadDbRow($r); }, $rows);
    return $result;
  }

  public static function availablePages() {
    return json_decode(json_encode([ //Folder does not have to possess rights. Page does not have to possess subMenus
      ['text'=>'User Management', 'icon'=>'users', 'url'=>'', 'subMenus'=>[
        ['text'=>'Users', 'icon'=>'users', 'url'=>'user/users','subMenus'=>[],'rights'=>['create', 'update', 'delete', 'changePass']],
        ['text'=>'Access', 'icon'=>'credit card outline', 'url'=>'user/access', 'subMenus'=>[],'rights'=>['create', 'update', 'delete']],
        ['text'=>'Testing', 'icon'=>'question', 'url'=>'', 'subMenus'=>[
          ['text'=>'Test Child', 'icon'=>'user', 'url'=>'', 'subMenus'=>[
            ['text'=>'Test GrandChild', 'icon'=>'users', 'url'=>'user/grand-child', 'subMenus'=>[], 'rights'=>['create', 'update', 'delete']]
          ]],
        ]],
      ]], //User Management
    ]));
  }

  public function loadPages() {
    if ($this->_pages !== null) return; //If already loaded, no need to reload.
    $this->_pages = AccessPage::allPlus('WHERE access_fk=:ID','*', ['ID'=>$this->id]);
    $this->buildMenuTree();
  }
  private function buildMenuTree() { //Used in "loadPages"
    $this->_menuTree = $this->menuRightsTrim($this->availablePages(), $this->_pages);
  }

  public static function menuRightsTrim($menuTree, $pageUrlRights) {
    $result = [];
    $pageRights = [];
    foreach ($pageUrlRights as $p) $pageRights[$p->url] = $p->rights;
    $pageUrls = array_keys($pageRights);
    foreach ($menuTree as &$m) {
      //When not a folder (is a page), check "url", if ok, put into result
      if (in_array($m->url, $pageUrls)) {
        $m->rights = $pageRights[$m->url];
        $result[] = $m;
        continue; //Page has no children. So no need to check subMenus
      }
      //Check if a folder has children. Only if has children, will put into result.
      $m->subMenus = self::menuRightsTrim($m->subMenus, $pageUrlRights);
      if (count($m->subMenus) > 0) $result[] = $m;
    }
    return $result;
  }

  public function getMenus() {
    $this->loadPages();
    return $this->_menuTree;
  }

  public function canAccess($url, $access='') {
    if ($this->role === 'admin') return true;

    $this->loadPages();
    //Check href in _pages
    $filter = array_filter($this->_pages, function($p) use ($url) { return $p->url === $url; });
    if (count($filter) < 1) return false;
    //Check rights in AccessPage
    if ($access === '') return true; //Means, this Access, got AccessPage with $url, don't care about rights.
    $page = $filter[0];
    if (!in_array($access, $page->rights)) return false;
    return true;
  }
}