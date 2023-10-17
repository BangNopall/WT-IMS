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
	return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('dashboard', function () {
	return view('layouts.master');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('kategori', 'CategoryController');
	Route::get('/apiKategori', 'CategoryController@apiCategories')->name('api.kategori');
	Route::get('/exportKategoriAll', 'CategoryController@exportCategoriesAll')->name('exportPDF.kategoriAll');
	Route::get('/exportKategoriesAllExcel', 'CategoryController@exportExcel')->name('exportExcel.kategoriAll');

	Route::resource('anggota', 'CustomerController');
	Route::get('/apiAnggota', 'CustomerController@apiCustomers')->name('api.anggota');
	Route::post('/importAnggota', 'CustomerController@ImportExcel')->name('import.anggota');
	Route::get('/exportAnggotaAll', 'CustomerController@exportCustomersAll')->name('exportPDF.anggotaAll');
	Route::get('/exportAnggotaAllExcel', 'CustomerController@exportExcel')->name('exportExcel.anggotaAll');

	Route::resource('sales', 'SaleController');
	Route::get('/apiSales', 'SaleController@apiSales')->name('api.sales');
	Route::post('/importSales', 'SaleController@ImportExcel')->name('import.sales');
	Route::get('/exportSalesAll', 'SaleController@exportSalesAll')->name('exportPDF.salesAll');
	Route::get('/exportSalesAllExcel', 'SaleController@exportExcel')->name('exportExcel.salesAll');

	Route::resource('barang', 'ProductController');
	Route::get('/barang/create', 'ProductController@create')->name('barang.create');
	Route::get('/barang/edit/{id}', 'ProductController@edit')->name('barang.edit');
	Route::post('/barang/update', 'ProductController@update')->name('barang.update');
	Route::post('/barang/store', 'ProductController@store')->name('barang.store');
	Route::get('/apiBarang', 'ProductController@apiProducts')->name('api.barang');

	Route::resource('wdbarang', 'ProductKeluarController');
	Route::post('/wdbarang/delete/{id}', 'ProductKeluarController@destroy')->name('wdbarang.delete');
	Route::get('/apiWdbarang', 'ProductKeluarController@apiProductsOut')->name('api.wdbarang');
	Route::get('/exportWdBarangAll', 'ProductKeluarController@exportProductKeluarAll')->name('exportPDF.wdbarangAll');
	Route::get('/exportWdBarangAllExcel', 'ProductKeluarController@exportExcel')->name('exportExcel.wdbarangAll');
	Route::get('/exportWdBarang/{id}', 'ProductKeluarController@exportProductKeluar')->name('exportPDF.wdbarang');

	Route::resource('dpbarang', 'ProductMasukController');
	Route::post('/dpbarang/delete/{id}', 'ProductMasukController@destroy')->name('dpbarang.delete');
	Route::get('/apiDpBarang', 'ProductMasukController@apiProductsIn')->name('api.dpbarang');
	Route::get('/exportDpBarangAll', 'ProductMasukController@exportProductMasukAll')->name('exportPDF.dpbarangAll');
	Route::get('/exportDpBarangAllExcel', 'ProductMasukController@exportExcel')->name('exportExcel.dpbarangAll');
	Route::get('/exportDpBarang/{id}', 'ProductMasukController@exportProductMasuk')->name('exportPDF.dpbarang');

	Route::get('/testdiscord', 'DiscordController@index')->name('index.discord');

	Route::resource('user', 'UserController');
	Route::get('/apiUser', 'UserController@apiUsers')->name('api.users');
});
