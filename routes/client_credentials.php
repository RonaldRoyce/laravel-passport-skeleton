<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('client_credentials')->get('/my', function (Request $request) {
    return array('user_id' => 1);
});

Route::get('oauth/token', 'AuthController@auth');

Route::middleware('client_credentials')->get('/role/add', 'Api\RoleApiController@create')->name('rolecreate');
Route::middleware('client_credentials')->get('/role/delete', 'Api\RoleApiController@delete')->name('roledelete');
Route::middleware('client_credentials')->get('/rolepermission/get', 'Api\RoleApiController@getRolePermissions')->name('rolepermissionget');
Route::middleware('client_credentials')->get('/rolepermission/add', 'Api\RoleApiController@addRolePermissions')->name('rolepermissionadd');


Route::middleware('client_credentials')->get('/permission/add', 'Api\RoleApiController@addPermission')->name('permissionadd');

Route::middleware('client_credentials')->get('/menu/add', 'Api\MenuApiController@createMenu')->name('menuadd');
Route::middleware('client_credentials')->get('/menuitem/save', 'Api\MenuItemApiController@saveMenuItem')->name('menuitemsave');
Route::middleware('client_credentials')->get('/menuitem/add', 'Api\MenuItemApiController@createMenuItem')->name('menuitemadd');
Route::middleware('client_credentials')->get('/menuitem/delete', 'Api\MenuItemApiController@deleteMenuItem')->name('menuitemdelete');
