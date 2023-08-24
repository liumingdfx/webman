<?php

namespace app\controller;

use support\Request;
use Webman\Push\Api;
use app\model\ChatRoom;
use Tinywan\Jwt\JwtToken;

class UserController
{
   public function user()
   {
       $user = JwtToken::getUser();

       return json($user);
   }


   public function roomList()
   {
       $user = JwtToken::getUser();

       $list = ChatRoom::query()->get();

       return json($list);
   }


   public function joinRoom()
   {

   }

}
