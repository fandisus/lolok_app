<?php

use LolokApp\Access;
use LolokApp\User;
use LolokApp\AccessPage;

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
      (object)['id'=>1, 'name'=>'All', 'created_at'=>'2022-05-08', 'updated_at'=>'2022-05-08'],
    ];
    Access::multiInsert($accesses);

    $accessPages = [
      (object)['access_fk'=>1, 'key'=>'/user/user-management', 'rights'=>['getUsers', 'saveUser', 'delUser', 'changePass', 'getAccesses', 'saveAccess', 'delAccess']],
    ];
    
    AccessPage::multiInsert($accessPages, 10000, true);

    // $ap = new AccessProfile([
    //   'name'=>'All',
    //   'menu_tree'=>AccessProfile::availableMenus()
    // ]);
    // $ap->insert();

    // $ua = new UserAccess([
    //   'uid'=> 2,
    //   'profile'=>'All'
    // ]);
    // $ua->insert();

  }
}