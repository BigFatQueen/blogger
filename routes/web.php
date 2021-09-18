<?php

use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
})->name('login');

Auth::routes();

//Backend
Route::group(['prefix'=>'admin','as'=>'admin.', 'middleware' => ['role:admin|creator']], function(){
	Route::get('/dashboard','DashboardController@index')->name('dashboard');
	Route::resource('category', 'CategoryController');
	Route::resource('permission', 'PermissionController');
	Route::resource('role', 'RoleController');
	Route::resource('user', 'UserController');
	Route::post('user/inactive/{id}', 'UserController@inActive')->name('user.inactive');

	
});