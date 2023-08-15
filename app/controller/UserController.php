<?php

namespace app\controller;

use support\Request;
use Webman\Push\Api;
use Tinywan\Jwt\JwtToken;

class UserController
{
   public function user()
   {
       $user = JwtToken::getUser();

       return json($user);
   }

}
