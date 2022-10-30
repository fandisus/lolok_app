<?php
namespace LolokApp;

use Fandisus\Lolok\Model;

/*
This class represents an Access Profile.
Represents a profile of menu structure, and access rights.
*/
class AccessPage extends Model {
  protected static function tableName() { return 'access_pages'; }
  protected static function PK() { return ['access_fk', 'url']; }
  protected static function hasSerial() { return false; }
  protected static function jsonColumns() { return ['rights']; }
  public $access_fk, $url, $rights;
}