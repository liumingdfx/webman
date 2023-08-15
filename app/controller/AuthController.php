<?php

namespace app\controller;

use app\Exception\model\User;
use support\Request;
use Webman\Push\Api;
use support\Response;
use yzh52521\hash\Hash;

class AuthController
{

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::query()->where('name', $username)->first();

        if(!$user) {
            return json(['message' => '用户不存在'])->withStatus(403);
        }

        //检查密码
        if(Hash::check($user->passwrod, $password)) {
            //登录成功
            return json(['message' => '登录成功'])->withStatus(200);
        }
        return json(['message' => '密码错误'])->withStatus(403);

    }

    public function register(Request $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');

        $exists = User::query()->where('name', $username)->exists();

        if($exists) {
            return json(['message' => '该用户已注册'])->withStatus(403);
        }

        $user = new User();
        $user->name = $username;
        $user->email = $email;
        $user->password = Hash::make($password);
        try {
            $user->save();
            return json(['message' => '注册成功']);
        }catch (\Exception $exception) {
            return json(['message' => '注册失败'])->withStatus(500);
        }
    }
}
