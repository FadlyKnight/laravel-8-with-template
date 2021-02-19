<?php

use Illuminate\Support\Facades\Auth;
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

Route::group(['prefix' => 'auth'],function (){
    Route::get('/{provider}', 'SocialiteController@redirect');
    Route::get('/{provider}/callback', 'SocialiteController@callback');
  
    // Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
    // Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    // http://localhost:8000/auth/
});

Route::get('/datates', function () {
    if (Auth::check()) {
        return 'Is Login';
    } else {
        return 'Is NOT Login';
    }
});
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
