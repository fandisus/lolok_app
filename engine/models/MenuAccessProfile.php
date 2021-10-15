<?php
namespace LolokApp;
use Fandisus\Lolok\Model;

class MenuAccessProfile extends Model {
  protected static function tableName() { return 'menu_access_profiles'; }
  protected static function PK() { return ['profile','id_menu_akses']; }
  protected static function hasSerial() { return false; }
  protected static function jsonColumns() { return []; }

  public $profile, $id_menu_akses;
}