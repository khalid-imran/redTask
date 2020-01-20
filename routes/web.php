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

//============================
// Public Home Page
//============================
Route::get('/', 'FrontController@Index')->name('index');


//============================
// Authentication
//============================
Route::get('/sign-up', 'FrontController@signUp')->name('index.signUp')->middleware('UserNotRoutePermission');
Route::post('/sign-up', 'FrontController@signUpAction')->name('index.signUp.action');
Route::get('/login', 'FrontController@login')->name('index.login')->middleware('UserNotRoutePermission');
Route::post('/login', 'FrontController@loginAction')->name('index.login.action');
Route::get('/logout', 'FrontController@logout')->name('index.logout');


//============================
// Dashboard
//============================
Route::get('/dashboard', 'FrontController@dashboard')->name('index.dashboard')->middleware('UserRoutePermission');


//============================
// Projects
//============================
Route::get('/projects', 'FrontController@projects')->name('index.projects')->middleware('UserRoutePermission');

Route::post('/projectsCreate', 'FrontController@projectsCreate')->name('index.projects.create');
Route::post('/projectsEdit', 'FrontController@projectsEdit')->name('index.projects.edit');
Route::post('/projectsDelete', 'FrontController@projectsDelete')->name('index.projects.delete');
Route::get('/projects/single/{id}', 'FrontController@projectSingle')->name('index.projects.single')->middleware('UserRoutePermission');

//============================
// Card
//============================
Route::post('/projects/card', 'FrontController@projectCards')->name('index.projects.card');
Route::post('/projects/card/edit', 'FrontController@projectCardsEdit')->name('index.projects.card.edit');
Route::post('/projects/card/delete', 'FrontController@projectCardsDelete')->name('index.projects.card.delete');

Route::get('/projects/single/card/{id}', 'FrontController@projectSingleCard')->name('index.projects.single.card')->middleware('UserRoutePermission');

//============================
// Profile
//============================
Route::get('/profile', 'FrontController@profile')->name('index.profile')->middleware('UserRoutePermission');
Route::post('/profileUpdate', 'FrontController@profileUpdate')->name('index.profile.update');
Route::post('/passUpdate', 'FrontController@passUpdate')->name('index.profile.password');
Route::post('/emailUpdate', 'FrontController@emailUpdate')->name('index.profile.email');

//============================
// Media
//============================
Route::post('/media', 'MediaController@media')->name('index.media');



