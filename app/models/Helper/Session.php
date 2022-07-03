<?php
namespace LolokApp\Helper;

use LolokApp\Access;
use LolokApp\User;
use LolokApp\UserLogin;

class Session {
  public static ?Session $obj;
  public ?object $oJwt;
  public ?User $user;
  public ?UserLogin $login;
  public ?Access $currentAccess;
  public ?array $available_accesses;
  public function __construct() {
    self::$obj = $this;
  }
}