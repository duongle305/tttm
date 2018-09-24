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
    Route::get('change-local-transfers','LocalTransferController@index')->name('local-transfers.index');
    Route::post('ajax/nodes','LocalTransferController@getNodes')->name('local-transfers.nodes');
    Route::post('ajax/get-assets-by-node','LocalTransferController@getAssetsByNode')->name('local-transfers.assets');
    Route::post('ajax/node-to-node','LocalTransferController@nodeToNode')->name('local-transfers.assets.submit');

    //repository transfers
    Route::get('/ware-house-transfers','LocalTransferController@wareHouseTransfers')->name('local-warehouse-transfers.create');
    Route::post('ajax/warehouses','LocalTransferController@getWareHouseTransfers')->name('local-transfers.warehouses');
    Route::post('ajax/assets','LocalTransferController@getAssetByWareHouseId')->name('local-transfers.assets');
    Route::post('/ajax/checkQuantity','LocalTransferController@checkQuantity')->name('local-transfers.quantity');
    Route::post('/ajax/warehouse-to-node','LocalTransferController@wareHouseToNode')->name('local-transfers.warehouse-to-node');

    Route::get('users', 'ChangeRegisterController@users')->name('cr.users');
    Route::get('change-registers/all','ChangeRegisterController@allChangeRegister')->name('change-registers.all');
    Route::resource('change-registers','ChangeRegisterController')->except(['show']);
});