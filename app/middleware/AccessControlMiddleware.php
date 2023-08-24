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
use Firebase\JWT\SignatureInvalidException;
use Tinywan\Jwt\Exception\JwtTokenException;
use Tinywan\Jwt\Exception\JwtTokenExpiredException;

/**
 * Class AuthCheckMiddleware
 * @package app\middleware
 */
class AccessControlMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {
        // 如果是options请求则返回一个空响应，否则继续向洋葱芯穿越，并得到一个响应
        $response = $request->method() == 'OPTIONS' ? response('') : $handler($request);

        // 给响应添加跨域相关的http头
        $response->withHeaders([
                                   'Access-Control-Allow-Credentials' => 'true',
                                   'Access-Control-Allow-Origin' => $request->header('origin', '*'),
                                   'Access-Control-Allow-Methods' => $request->header('access-control-request-method', '*'),
                                   'Access-Control-Allow-Headers' => $request->header('access-control-request-headers', '*'),
                               ]);

        return $response;


    }
}
