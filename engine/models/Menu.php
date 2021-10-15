<?php
namespace LolokApp;
use Fandisus\Lolok\Model;

class Menu extends Model {
  protected static function tableName() { return 'menus'; }
  protected static function PK() { return ['name']; }
  protected static function hasSerial() { return false; }
  protected static function jsonColumns() { return []; }

  public $name, $position, $text, $href, $icon, $parent;
}