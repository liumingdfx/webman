<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace app\middleware;

use Tinywan\Jwt\JwtToken;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;
use Firebase\JWT\ExpiredException;
use Tinywan\Jwt\Exception\JwtTokenExpiredException;

/**
 * Class AuthCheckMiddleware
 * @package app\middleware
 */
class AuthCheckMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {




        // 通过反射获取控制器哪些方法不需要登录
        $controller = new \ReflectionClass($request->controller);
        $noNeedLogin = $controller->getDefaultProperties()['noNeedCheckLogin'] ?? [];
        // 访问的方法需要登录
        if (in_array($request->action, $noNeedLogin)) {
            // 不需要登录，请求继续向洋葱芯穿越
            return $handler($request);
        }

        try {
            $auth = JwtToken::verify();
        }catch (JwtTokenExpiredException $exception){
            //令牌已过期
            return json(['message' => 'TOKEN EXPIRED'])->withStatus(423);
        }

        if (isset($auth['extend']['id'])) {
            // 已经登录，请求继续向洋葱芯穿越
            return $handler($request);
        }

        //拦截请求，返回一个重定向响应，请求停止向洋葱芯穿越
        return json(['message' => '未登录'])->withStatus(401);


    }
}
