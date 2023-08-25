<?php

use GatewayWorker\Lib\Gateway;

/**
 * Here is your custom functions.
 */


/**
 * @param          $client_id
 * @param  string  $msg
 * @param  array   $data
 * User: LiuMing
 * Date: 2023/8/24 10:57
 * 向个人发送消息
 *
 */
function send($client_id,string $type, string $msg = '请求成功', array $data = [])
{
    Gateway::sendToClient(
        $client_id,
        json_encode([
                        'msg' => $msg,
                        'type' => $type,
                        'data' => $data,
                    ], JSON_UNESCAPED_UNICODE)
    );
}


function send_to_group($roomId,string $type, string $msg = '请求成功', array $data = [])
{
    Gateway::sendToGroup(
        $roomId,
        json_encode([
                        'msg' => $msg,
                        'type' => $type,
                        'data' => $data,
                    ], JSON_UNESCAPED_UNICODE)
    );
}


function cacheMsg($roomId, $data)
{
    \support\Redis::lPush('chatLog:'.$roomId, json_encode($data));
}

function getLogCache($roomId): array
{
    $res =  \support\Redis::lRange('chatLog:'.$roomId,0 , -1);

    foreach ($res as &$re)
    {
        $re = json_decode($re, true);
    }

    return array_reverse($res);
}
