<?php

use LolokApp\AccessProfile;
use LolokApp\User;
use LolokApp\UserAccess;

class Users {
  public static function run() {
    // DB::insert('INSERT INTO xyz VALUES (1,2,3)',[]);

    $users = [
      (object)[
        'id'=>1, 'username'=>'admin', 'fullname'=>'administrator', 'password'=>User::hashPassword('admin'),
        'email'=>'admin@admina.com', 'phone'=>'123123', 'jwt'=>''
      ],
      (object)[
        'id'=>2, 'username'=>'fandi', 'fullname'=>'Fandi Susanto', 'password'=>User::hashPassword('fandi'),
        'email'=>'fandi@admina.com', 'phone'=>'123123', 'jwt'=>''
      ],
    ];
    User::multiInsert($users);

    $ap = new AccessProfile([
      'name'=>'All',
      'menu_tree'=>AccessProfile::availableMenus()
    ]);
    $ap->insert();

    $ua = new UserAccess([
      'uid'=> 2,
      'profile'=>'All'
    ]);
    $ua->insert();

    // $users = [
    //   ['id'=>1, 'username'=>'admin', 'password'=>User::hashPassword('admin'), 'email'=>'admin@admina.com', 'phone'=>'123123'],
    //   ['id'=>2, 'username'=>'fandi', 'password'=>User::hashPassword('fandi'), 'email'=>'fandi@admina.com', 'phone'=>'123123'],
    // ];

    // User::multiInsert($users);

    // foreach ($users as $u) {
    //   $oUser = new User($u);
    //   $oUser->insert();
    // }
  }
}