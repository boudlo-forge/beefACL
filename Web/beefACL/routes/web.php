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

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');;

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('password/change', 'UserController@passwordForm')->name('password.change')->middleware('auth');
Route::post('password/new', 'UserController@updatePassword')->name('password.new')->middleware('auth');

// Email Verification Routes...
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify'); // v6.x
/* Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify'); // v5.x */
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// User Management, must be super admin for now.
Route::get('/user/{id}/edit', 'UserController@form')->name('user.edit')->middleware('auth','flags','role:'. User::ROLE_SUPER_ADMIN);
Route::get('/users', 'UserController@list')->name('user.list')->middleware('auth','flags', 'role:'. User::ROLE_SUPER_ADMIN);
Route::post('/users', 'UserController@store')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::post('/user/{id}', 'UserController@store')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::get('/user/{id}', 'UserController@show')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);

Route::get('/node/new', 'NodeController@form')->name('node.new')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::get('/node/{id}/edit', 'NodeController@form')->name('node.edit')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::get('/nodes', 'NodeController@list')->name('node.list')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::post('/nodes', 'NodeController@store')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::post('/node/{id}', 'NodeController@store')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);

Route::get('/access_category/new', 'AccessCategoryController@form')->name('access_category.new')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::get('/access_category/{id}/edit', 'AccessCategoryController@form')->name('access_category.edit')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::get('/access_categories', 'AccessCategoryController@list')->name('access_category.list')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);
Route::post('/access_categories', 'AccessCategoryController@store')->middleware('auth', 'role:'. User::ROLE_SUPER_ADMIN);