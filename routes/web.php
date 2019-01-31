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
    return view('configuration');
});


Route::get('/search', function () {
    return view('search');
});

Route::get('/configuration', function () {
    return view('configuration');
});

Route::get('/update', function () {
    return view('updateRecord');
});