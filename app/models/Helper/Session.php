<?php
namespace LolokApp\Helper;

use LolokApp\Access;
use LolokApp\User;
use LolokApp\UserLogin;

class Session {
  public static ?object $oJwt = null;
  public static ?User $user = null;
  public static ?UserLogin $login = null;
  public static ?Access $currentAccess = null;
  public static ?array $available_accesses = null;
}