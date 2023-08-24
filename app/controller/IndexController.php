<?php

namespace app\controller;

use support\Request;
use Webman\Push\Api;

class IndexController
{
    protected $noNeedLogin = ['test'];
    public function test(){
        return json([123123]);
    }
    public function index(Request $request)
    {
        $api = new Api(
            config('plugin.webman.push.app.api'),
            config('plugin.webman.push.app.app_key'),
            config('plugin.webman.push.app.app_secret')
        );
        $msg = $request->input('msg', '默认消息');

        // 给订阅 user-1 的所有客户端推送 message 事件的消息
        $api->trigger('user-1', 'message', [
            'from_uid' => 2,
            'content'  =>$msg
        ]);
    }

    public function view(Request $request)
    {
        return view('index/view', ['name' => 'webman']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
