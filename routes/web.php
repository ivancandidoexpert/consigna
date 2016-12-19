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

//Route::resource('cliente','ClienteController'); 

Route::auth();

Route::get('/', function(){ 
	return redirect(url('/clientes'));
	});

Route::get('/cliente', 'ClienteController@index');
Route::post('/cliente/show', 'ClienteController@show');
Route::post('/cliente/create', 'ClienteController@create');
Route::put('/cliente/{client_id}/update', 'ClienteController@update');
Route::get('/cliente/{client_id}/followup', 'ClienteController@showFollowUpPanel');
Route::get('/cliente/{client_id}/extrato', 'ClienteController@showExtrato');
Route::get('/cliente/{client_id}/mailextrato', 'ClienteController@sendExtratoEmail');
Route::get('/cliente/{client_id}/mailremessa', 'ClienteController@sendRemessaEmail');


//Route::get('/cliente/{client_id}/remessa', 'TransactionController@getFormularioRemessa');

Route::get('/cliente/{cliente_id}/transaction/remessa','TransactionController@createRemessa');
Route::post('/cliente/{cliente_id}/transaction/remessa', 'TransactionController@storeRemessa');
Route::get('/cliente/{cliente_id}/transaction/fatura','TransactionController@createFatura');
Route::post('/cliente/{cliente_id}/transaction/fatura', 'TransactionController@storeFatura');


Route::get('/clientes', 'ClienteController@showAll');
Route::get('/clientes/followup', 'ClienteController@showFollowUpList'); 
Route::get('/clientes/afaturar', 'TransactionController@showListaClientesAFaturar');
Route::get('/clientes/aremeter', 'TransactionController@showListaClientesARemeter');
Route::post('/clientes/aremeter', 'TransactionController@storeRemessaExpress');

Route::get('/transaction', 'TransactionController@index'); 
Route::post('/transaction', 'TransactionController@store');
Route::post('/transaction/delete', 'TransactionController@delete');

Route::get('/stock/listar', 'TransactionController@showListarEstoques');
Route::post('/stock/update', 'TransactionController@updateStock');
Auth::routes();

Route::get('/home', 'HomeController@index');
