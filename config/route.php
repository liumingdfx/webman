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

use Webman\Route;
use app\controller\AuthController;
use app\controller\UserController;
use app\controller\IndexController;

Route::get('/test', [IndexController::class, 'test']);

Route::get('/home', [IndexController::class, 'view']);
Route::post('/send', [IndexController::class, 'index']);


Route::post('/api/auth/login', [AuthController::class, 'login']);
Route::post('/api/auth/register', [AuthController::class, 'register']);
Route::post('/api/auth/refresh', [AuthController::class, 'refreshToken']);

Route::get('/api/user', [UserController::class,'user']);


Route::get('/api/chat/room', [UserController::class, 'roomList']);
Route::post('/api/chat/join', [UserController::class, 'joinRoom']);








