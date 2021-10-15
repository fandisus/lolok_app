<?php
namespace LolokApp;
use Fandisus\Lolok\Model;

class MenuAccess extends Model {
  protected static function tableName() { return 'menu_accesses'; }
  protected static function PK() { return ['id']; }
  protected static function hasSerial() { return true; }
  protected static function jsonColumns() { return []; }

  public $id, $menu, $akses;
}