<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('v1/login', 'Api\UserController@login');
Route::post('v1/register', 'Api\UserController@register');

Route::group(['prefix' => 'v1', 'middleware' => ['jwt.verify']], function () {
    Route::get('logout','Api\UserController@logout');
    Route::get('user','Api\UserController@user');
});

//Creator / User
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.verify', 'role:admin|creator|user']], function () {
    Route::resource('category','Api\CategoryController');
});

//Creator
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.verify', 'role:creator|user']], function () {
    Route::resource('subscription-plan','Api\SubscriptionPlanController');
    Route::resource('content','Api\ContentController');
    Route::resource('like','Api\LikeController');
    Route::resource('comment','Api\CommentController');
    Route::resource('poll','Api\PollController');
    Route::resource('subscription','Api\SubscriptionController');
});

Route::get('v1/login/{provider}', 'SocialController@redirect');
Route::get('v1/login/{provider}/callback','SocialController@Callback');