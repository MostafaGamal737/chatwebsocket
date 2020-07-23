<?php

use Illuminate\Support\Facades\Route;
use App\Events\sendMessageEvent;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //event(new WebSocketEvent("welcome to web socket"));
    return view('welcome');
});
Route::get('chat','chatController@index');
Route::get('messages','chatController@getmessages');
Route::post('messages','chatController@sendmessages');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
