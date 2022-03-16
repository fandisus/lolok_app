<?php
namespace LolokApp;
use Fandisus\Lolok\Model;

class Setting extends Model {
  protected static function tableName() { return 'settings'; }
  protected static function PK() { return ['name']; }
  protected static function hasSerial() { return false; }
  protected static function jsonColumns() { return []; }

  public $name, $setting_value;
}