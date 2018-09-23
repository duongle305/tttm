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

Route::get('/', function(){
    if(auth()->check()) return redirect()->to('/home');
    return redirect()->route('auth.login');
});

Route::get('/home', function () {
    return view('change_registers.index');
});
Route::get('auth/login','Auth\LoginController@showLoginForm')->name('auth.login');
Route::post('/auth/login','Auth\LoginController@login');
Route::middleware('auth')->group(function(){
    Route::get('auth/logout','Auth\LoginController@logout')->name('auth.logout');
    Route::get('/home', function () {
        return view('change_registers.index');
    });
    //local transfer
    Route::get('change-local-transfers','LocalTransferController@index')->name('local_transfers.index');
    Route::post('ajax/nodes','LocalTransferController@getNodes');

    Route::get('users', 'ChangeRegisterController@users')->name('cr.users');
    Route::get('change_registers/all','ChangeRegisterController@allChangeRegister')->name('change_registers.all');
    Route::resource('change_registers','ChangeRegisterController')->except(['show']);
});