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
Route::group(['prefix' => 'v1'], function () {
    Route::post('auth/login', 'Api\UserController@login');
    Route::post('auth/register', 'Api\UserController@register');

    Route::post('auth/{provider}', 'Api\SocialController@login');
    Route::get('auth/{provider}/callback', 'Api\SocialController@callback');
    
    Route::get('auth/phone/send-sms/{no}', 'Api\SmsController@sendSMS');
    Route::post('auth/phone/register', 'Api\SmsController@register');
    Route::post('auth/phone/login', 'Api\SmsController@login');
    Route::get('user/search','Api\UserController@userSearch')->name('user.search');
    Route::get('user/creator','Api\UserController@creator')->name('user.creator');
});   

Route::group(['prefix' => 'v1', 'middleware' => ['jwt.verify']], function () {
//Route::group(['prefix' => 'v1'], function () {
    Route::get('logout','Api\UserController@logout');
    Route::get('user','Api\UserController@user');
    Route::post('user/update','Api\UserController@update')->name('user.update');
    Route::get('user/get-creators','Api\UserController@getCreators')->name('user.get-creators');
});

//Creator / User
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.verify', 'role:admin|creator|user']], function () {
// Route::group(['prefix' => 'v1'], function () {
    Route::resource('category','Api\CategoryController');
});

//Creator
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.verify', 'role:creator|user']], function () {
// Route::group(['prefix' => 'v1'], function () {
    Route::get('region','Api\RegionController@index');
    Route::resource('subscription-plan','Api\SubscriptionPlanController');
    Route::resource('content','Api\ContentController');
    Route::resource('like','Api\LikeController');
    Route::resource('comment','Api\CommentController');
    Route::resource('comment-reply','Api\CommentReplyController');
    Route::resource('comment-like','Api\CommentLikeController');
    Route::resource('poll-option','Api\PollOptionController');
    Route::resource('poll','Api\PollController');
    Route::resource('subscription','Api\SubscriptionController');
    Route::get('creator/subscriber','Api\SubscriptionController@creatorSubscriber');
    Route::get('creator/rsmanager','Api\SubscriptionController@rsManager');
    Route::get('creator/earnings','Api\SubscriptionController@earnings');
    Route::resource('filter','Api\FilterController');
});