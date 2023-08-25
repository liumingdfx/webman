<?php

namespace plugin\webman\gateway;

use app\model\User;
use app\model\ChatRoom;
use Tinywan\Jwt\JwtToken;
use app\assistants\MsgType;
use GatewayWorker\Lib\Gateway;

class Events
{
    public static function onWorkerStart($worker)
    {
    }

    public static function onConnect($client_id)
    {
    }

    public static function onWebSocketConnect($client_id, $data)
    {
        //拿到token校验
        $getData = $data['get'];
        try {
            $userInfo = JwtToken::verify(1, $getData['token']);
        } catch (\Exception $exception) {
            send($client_id, MsgType::LOGIN_ERROR, '账号信息错误，请重新登录');
            sleep(1); // 延迟一秒断开链接

            return Gateway::closeClient($client_id);
        }
        $uid = $userInfo['extend']['id'];
        $nickname = $userInfo['extend']['nickname'];
        $avatar = $userInfo['extend']['avatar'];
        echo $uid;
        //重复上线验证
        if (count(Gateway::getClientIdByUid($uid))) {
            send($client_id, MsgType::LOGIN_REPEAT, '已经登录，请勿重复登录');
            sleep(1); // 延迟一秒断开链接

            return Gateway::closeClient($client_id);
        }

        //uid绑到clientId
        Gateway::bindUid($client_id, $uid);
        //将uid和clinentId存session
        $_SESSION[$client_id] = $uid;

        $roomList = ChatRoom::query()->get();

        send($client_id, MsgType::LOGIN, '连接成功', [
            'client_id' => $client_id,
            'room_list' => $roomList,
            'nickname' => $nickname,
            'avatar' => $avatar
        ]);
    }

    public static function onMessage($client_id, $message)
    {

        $message = json_decode($message, true);



        $data    = $message['data'] ?? [];

        $token   = $data['token'];

        $userInfo = JwtToken::verify(1, $token);;
        $nickname = $userInfo['extend']['nickname'];
        $avatar = $userInfo['extend']['avatar'];
        $uid      = $userInfo['extend']['id'];

        switch ($message['type']) {
            case 'join':
                // 加入房间
                Gateway::joinGroup($client_id, $data['room_id']);
                // 获取房间用户列表
                $roomUserList  = Gateway::getUidListByGroup($data['room_id']);
                $finalUserList = User::query()->whereIn('id', $roomUserList)->get(['id', 'name','avatar', 'nickname']);
                // 向房间广播
                send_to_group($data['room_id'], MsgType::JOIN, "{$nickname}加入房间", [
                    'nickname'    => $nickname,
                    'online_num'  => count($finalUserList),
                    'online_list' => $finalUserList,
                    'chat_logs'   => getLogCache($data['room_id'])
                ]);
                break;
            case 'send':
                //将消息存到redis
                cacheMsg($data['room_id'], ['content' => $data['content'],
                                            'uid' => $uid,
                                            'nickname' => $nickname,
                                            'avatar' => $avatar,
                                            'time' => date('Y-m-d H:i:s')
                ]);

                // 用户发送消息
                send_to_group($data['room_id'], MsgType::SEND_MSG, '发送消息成功', [
                    'send_user_nickname' => $nickname,
                    'send_user_id'       => 1,
                    'send_content'       => $data['content'],
                    'chat_logs'          => getLogCache($data['room_id'])
                ]);


                break;
            default:
                send($client_id, MsgType::SUCCESS, '请求成功', ['client_id' => $client_id]);
                break;
        }
    }

    public static function onClose($client_id)
    {
        $uid = $_SESSION[$client_id];

        $userInfo = User::query()->where('id', $uid)->first();
        $nickname = $userInfo->nickname ?? '未知用户';
        //离开房间
        send_to_group(1, MsgType::LEAVE, "{$nickname}离开了房间", [
            'send_user_nickname' => $nickname,
            'send_user_id'       => 1,
            'send_content'       => "{$nickname}离开了房间",
        ]);
    }

}
