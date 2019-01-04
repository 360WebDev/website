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

Route::get('/', 'HomeController@index')->name('home.index');

Route::resource('blog', 'PostsController');
Route::get('blog/categorie/{slug}', 'PostsController@category')->name('blog.category');

Route::post('favorite/{post}',     'PostsController@favoritePost');
Route::post('unfavorite/{post}',   'PostsController@unFavoritePost');
Route::get('/compte/favorites',    'UsersController@myFavorites')->name('user.favorites')->middleware('auth');
Route::get('/compte',              'UsersController@myAccount')->name('user.account')->middleware('auth');
Route::put('/compte',              'UsersController@update')->name('user.update')->middleware('auth');
Route::get('/compte/mes-articles', 'UsersController@posts')->name('user.posts')->middleware('auth');
Route::get('/compte/article/nouveau',  'UsersController@addPost')->name('user.add.post')->middleware('auth');
Route::post('/compte/article/nouveau', 'UsersController@addPost')->name('user.add.post')->middleware('auth');
Route::get('/compte/article/modifier/{post}', 'UsersController@updatePost')->name('user.update.post')->middleware('auth');
Route::put('/compte/article/modifier/{post}', 'UsersController@updatePost')
	->name('user.update.post')
	->middleware('auth')
	->middleware(\App\Http\Middleware\CheckStatus::class);

// Admin Dashboard
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/', 'DashboardController@index')->name('admin.index');
    Route::resource('posts',      'PostsController');
    Route::resource('categories', 'CategoriesController');
    Route::resource('users',      'UserController');
    Route::resource('roles',      'RoleController');
});

Auth::routes();

