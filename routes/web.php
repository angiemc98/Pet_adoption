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
    return view('pets.index');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::view('pets/create', 'pets.create');
Route::view('/pets/edit', 'pets.edit');
Route::view('/applications', 'applications.index');
Route::view('/applications/manage', 'applications.manage');

Route::view('/dashboard', 'dashboard')->name('dashboard');

