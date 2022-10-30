<?php

use LolokApp\Access;
use LolokApp\User;
use LolokApp\AccessPage;
use LolokApp\UserAccess;

class UserSeed {
  public static function run() {
    // DB::insert('INSERT INTO xyz VALUES (1,2,3)',[]);

    $users = [
      (object) ['id'=>1, 'username'=>'admin', 'email'=>'admin@admina.com', 'phone'=>'', 'password'=>User::hashPassword('admin'),
      'fullname'=>'administrator', 'is_active'=>true],
      (object) ['id'=>2, 'username'=>'fandi', 'email'=>'fandi@admina.com', 'phone'=>'', 'password'=>User::hashPassword('fandi'),
      'fullname'=>'Fandi Susanto', 'is_active'=>true],
    ];
    User::multiInsert($users);

    $accesses = [
      (object)['id'=>1, 'name'=>'All', 'role'=>'admin', 'created_at'=>'2022-05-08', 'updated_at'=>'2022-05-08'],
    ];
    Access::multiInsert($accesses);

    $accessPages = [
      (object)['access_fk'=>1, 'url'=>'user/access', 'rights'=>['create', 'update', 'delete']],
      (object)['access_fk'=>1, 'url'=>'user/users', 'rights'=>['create', 'update', 'delete', 'changePass']],
      (object)['access_fk'=>1, 'url'=>'user/grand-child', 'rights'=>['create', 'update', 'delete']],
    ];
    AccessPage::multiInsert($accessPages, 10000, true);

    $userAccess = [
      (object)['id'=>1, 'user_fk'=>1, 'access_fk'=>1, 'created_at'=>'2022-05-16', 'updated_at'=>'2022-05-16'],
      (object)['id'=>2, 'user_fk'=>2, 'access_fk'=>1, 'created_at'=>'2022-05-16', 'updated_at'=>'2022-05-16'],
    ];
    UserAccess::multiInsert($userAccess);
  }
}