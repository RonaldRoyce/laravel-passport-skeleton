<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    return view('welcome');
});

Route::get('/notauthorized', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/oauthadmin', 'OauthController@index')->name('oauthadmin');
/*
Route::middleware('auth')->get('/callback', function (Request $request) {
    Log::debug("At top of callback");

    $client_id = env('PASSPORT_CLIENT_ID');
    $client_secret = env('PASSPORT_SECRET');

    $request->request->add([
        "grant_type" => "client_credentials",
        "client_id" => $client_id,
        "client_secret" => $client_secret,
    ]);

    $tokenRequest = $request->create(
        env('APP_URL', 'http://localhost') . '/oauth/token',
        'post'
    );

    $instance = Route::dispatch($tokenRequest);

    Log::debug("At bottom of callback");

    return json_decode($instance->getContent(), true);
});
*/

Route::middleware('auth')->get('/token', function (Request $request) {
    $client_id = env('PASSPORT_CLIENT_ID');
    $client_secret = env('PASSPORT_SECRET');

    $request->request->add([
                "grant_type" => "client_credentials",
                "client_id" => $client_id,
                "client_secret" => $client_secret,
        ]);

    $tokenRequest = $request->create(
        env('APP_URL', 'http://localhost') . '/oauth/token',
        'post'
    );

    $instance = Route::dispatch($tokenRequest);

    return json_decode($instance->getContent(), true);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/resetpassword', 'Auth\ResetPasswordController@index')->name('resetpassword')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('icons', ['as' => 'pages.icons', 'uses' => 'PageController@icons']);
    Route::get('maps', ['as' => 'pages.maps', 'uses' => 'PageController@maps']);
    Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'PageController@notifications']);
    Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'PageController@rtl']);
    Route::get('tables', ['as' => 'pages.tables', 'uses' => 'PageController@tables']);
    Route::get('typography', ['as' => 'pages.typography', 'uses' => 'PageController@typography']);
    Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'PageController@upgrade']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.index', 'uses' => 'ProfileController@index']);
    Route::get('profile/edit', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile/update', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});

Route::middleware('auth')->get('/admin/roles', 'Role\RoleController@index')->name('roles');
Route::middleware('auth')->get('/admin/rolepermissions', 'Role\RolePermissionsController@index')->name('rolepermissions');
Route::middleware('auth')->get('/admin/permissions', 'Role\PermissionController@index')->name('permissions');
