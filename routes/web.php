<?php
use Illuminate\Http\Request;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/oauthadmin', 'OauthController@index')->name('oauthadmin');

Route::middleware('auth')->get('/callback', function (Request $request) {
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
