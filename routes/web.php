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
use App\User;
use App\Notifications\NewSharedProject;
use Symfony\Component\HttpKernel\Exception\HttpException;

Route::get('/', function () {
	return view('pages.welcome');
})->name('welcome');

Auth::routes();

Route::get('sample-building-data-report', 'PageController@building')->name('page.building');

Route::prefix('dashboard')->middleware('auth')->group(function () {
	Route::get('/', 'HomeController@index')->name('home');
	Route::get('/{account}', 'HomeController@account')->name('account');
	Route::get('/{account}/{region}', 'HomeController@region')->name('region');
});

Route::get('invitation/create', 'InvitationController@create')->name('invitation.create');
Route::get('pricing', 'PageController@pricing')->name('page.pricing');
Route::get('termsandconditions', 'PageController@termsandconditions')->name('page.termsandconditions');
Route::get('faq', 'PageController@faq')->name('page.faq');

Route::get('reporting', 'PageController@reporting')->middleware('auth')->name('page.reporting'); //the report generated from a single geopoint in an accounts/dataset page

Route::get('reporting/project/{cluster_name}', 'PageController@clusterReporting')->middleware('auth')->name('page.cluster_reporting');//gives you reporting on a project
Route::get('pdf', 'PageController@pdf')->middleware('auth')->name('page.pdf');
Route::get('pdf/cluster/{cluster_name}', 'PageController@clusterPdf')->middleware('auth')->name('page.cluster_pdf');//the pdf version for download for a specific project

Route::get('lock', 'PageController@lock')->name('page.lock');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
	Route::get('/', 'PageController@admin');
});

Route::group(['middleware' => 'auth'], function () {
	Route::group(['middleware' => 'admin'], function () {
		Route::resource('category', 'CategoryController', ['except' => ['show']]);
		Route::resource('tag', 'TagController', ['except' => ['show']]);
		Route::resource('item', 'ItemController', ['except' => ['show']]);
		Route::resource('role', 'RoleController', ['except' => ['show', 'destroy']]);
		Route::resource('user', 'UserController', ['except' => ['show']]);
		Route::resource('account', 'AccountController');
		Route::resource('region', 'RegionController');
		Route::resource('organization', 'OrganizationController');
	});

	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	Route::post('invitation', 'InvitationController@store')->name('invitation.store');
	Route::post('clusters', 'ClusterController@store');
	Route::delete('clusters/{cluster}', 'ClusterController@delete');
	Route::post('add/geopoint', 'ClusterController@addGeopoint');
	Route::post('remove/geopoint', 'ClusterController@removeGeopoint');

    Route::post('share/clusters/{cluster_id}', 'ClusterController@shareCluster');

    Route::get('clusters', 'ClusterController@index');
	Route::get('projects/{cluster}', 'HomeController@cluster');
	//Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);

    Route::post('notifications/markAsRead', 'NotificationController@markAsRead');

    Route::get('/pro', 'HomeController@region_pro')->name('home.region_pro');
    // echo route('home.region_pro',['param1' => 'bear', 'param2' => 'rabbit']);

    Route::get('/test', 'ClusterController@findCluster');

});
