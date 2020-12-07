<?php

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
	return view('pages.welcome');
})->name('welcome');

Auth::routes();

Route::get('sample-building-data-report', 'PageController@building')->name('page.building');
Route::get('dashboard', 'HomeController@index')->name('home');
Route::get('pricing', 'PageController@pricing')->name('page.pricing');
Route::get('privacy', 'PageController@privacy')->name('page.privacy');
Route::get('faq', 'PageController@faq')->name('page.faq');
Route::get('reporting', 'PageController@reporting')->middleware('auth')->name('page.reporting');
Route::get('pdf', 'PageController@pdf')->middleware('auth')->name('page.pdf');
Route::get('lock', 'PageController@lock')->name('page.lock');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
	Route::get('/', 'PageController@admin');
	Route::prefix('region')->group(function () {
		Route::post('create', 'RegionController@create');
	});
	Route::apiResource('accounts', 'AccountController');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('category', 'CategoryController', ['except' => ['show']]);
	Route::resource('tag', 'TagController', ['except' => ['show']]);
	Route::resource('item', 'ItemController', ['except' => ['show']]);
	Route::resource('role', 'RoleController', ['except' => ['show', 'destroy']]);
	Route::resource('user', 'UserController', ['except' => ['show']]);

	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);
});
