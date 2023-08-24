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


Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

Route::get('/user', [UserController::class,'user']);


Route::get('/chat/room', [UserController::class, 'roomList']);
Route::post('/chat/join', [UserController::class, 'joinRoom']);








