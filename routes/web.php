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

//login
route::get('/login', 'ControllerLogin@login')->name('login');
Route::post('loginaksi', 'ControllerLogin@loginaksi')->name('loginaksi');
Route::get('logoutaksi', 'ControllerLogin@logoutaksi')->name('logoutaksi');

Route::group(['middleware' =>  ['auth', 'admin:admin,kasir 1,kasir 2']], function () {
    // Route::get('/', function () {
    //     return view('welcome');
    // });
    Route::get('/', 'ControllerDashboard@index');
    //transaksi
    Route::get('/transaksi', 'ControllerTransaksi@index');
    Route::get('/transaksi/shopping-chart', 'ControllerTransaksi@shoppingChart');
    Route::get('/transaksi/shopping-chart/delete/{id}', 'ControllerTransaksi@shoppingChartDelete');
    Route::post('/transaksi/bayar', 'ControllerTransaksi@pembayaran');

    //transaksi detail
    Route::get('/transaksi-detail', 'ControllerTransaksiDetail@index');
    Route::get('/transaksi-detail/{id}', 'ControllerTransaksiDetail@detail');
    Route::get('/transaksi-detail-return/{id}', 'ControllerTransaksiDetail@return');
    Route::get('/transaksi-detail-return/{id}/shopping-chart', 'ControllerTransaksiDetail@shoppingChart');
    Route::get('/transaksi-detail-return/{id}/shopping-chart/delete/{id_barang}', 'ControllerTransaksiDetail@shoppingChartDetele');
    Route::post('/transaksi-detail-return/bayar/{id}', 'ControllerTransaksiDetail@bayar');
    Route::get('/transaksi-detail-return/hapus/{id}', 'ControllerTransaksiDetail@hapus');
    Route::get('/transaksi-detail/print/{id}', 'ControllerTransaksiDetail@print');

    //data barang
    Route::get('/data-barang', 'ControllerDataBarang@index');
    Route::post('/data-barang/tambah', 'ControllerDataBarang@tambah');
    Route::post('/data-barang/tambah-barang-lama', 'ControllerDataBarang@tambahBarangLama');
    Route::get('/data-barang/edit/{id}', 'ControllerDataBarang@edit');
    Route::post('/data-barang/edit/{id}', 'ControllerDataBarang@dataEdit');
    Route::get('/data-barang/hapus/{id}', 'ControllerDataBarang@hapus');
    Route::get('/data-barang/penyusutan/{id}', 'ControllerDataBarang@penyusutan');
    Route::get('/data-barang/tambah-barang/{id}', 'ControllerDataBarang@edit');
    Route::post('/data-barang/tambah-barang/{id}', 'ControllerDataBarang@tambahStok');

    //hutang
    route::get('/hutang', 'ControllerHutang@index');
    route::get('/hutang/tambah', 'ControllerHutang@tambah');
    route::post('/hutang/{id}', 'ControllerHutang@bayar');
    route::get('/hutang/perusahaan', 'ControllerHutang@perusahaan');
    route::post('/hutang/perusahaan/{id}', 'ControllerHutang@perusahaanBayar');

    //Pendapatan lain - lain
    route::get('/pendapatan-lain', 'ControllerPendapatan@index');
    route::post('/pendapatan-lain/tambah', 'ControllerPendapatan@tambah');

    //pengeluaran Lain - lain
    route::get('/pengeluaran-lain', 'ControllerPengeluaran@index');
    route::post('/pengeluaran-lain/tambah', 'ControllerPengeluaran@tambah');

    //laporan
    route::get('/laporan', 'ControllerLaporan@index');
    //laporan pendapatan
    route::get('/laporan/laporan-pemasukan', 'ControllerLaporan@pemasukan');
    route::get('/laporan/laporan-pemasukan/get-data', 'ControllerLaporan@getData');
    //laporan pengeluaran
    route::get('/laporan/laporan-pengeluaran', 'ControllerLaporan@pengeluaran');
    route::get('/laporan/laporan-pengeluaran/get-data', 'ControllerLaporan@getDataPengeluaran');
    //laporan Hutang
    route::get('/laporan/laporan-hutang', 'ControllerLaporan@hutang');
    route::get('/laporan/laporan-hutang/get-data', 'ControllerLaporan@getDataHutang');
    //laporan Laba Rugi
    route::get('laporan/laporan-laba-rugi', 'ControllerLaporan@labaRugi');
    route::get('laporan/laporan-laba-rugi/get-data', 'ControllerLaporan@getDataLabaRugi');
    //laporan data barang
    route::get('laporan/laporan-data-barang', 'ControllerLaporan@dataBarang');
    route::get('laporan/laporan-data-barang/get-data', 'ControllerLaporan@getDataDataBarang');

    //data karyawan
    route::get('/data-karyawan', 'ControllerKaryawan@index');
    route::post('/data-karyawan/tambah', 'ControllerKaryawan@tambah');
    route::get('/data-karyawan/edit/{id}', 'ControllerKaryawan@getData');
    route::post('/data-karyawan/edit/{id}', 'ControllerKaryawan@edit');
    route::get('/data-karyawan/hapus/{id}', 'ControllerKaryawan@hapus');
    route::get('/data-karyawan/gaji-semua', 'ControllerKaryawan@gajiSemua');
    route::get('/data-karyawan/gaji/{id}', 'ControllerKaryawan@gaji');

    //Pengaturan Akun
    route::get('/pengaturan-akun', 'ControllerTambahAkun@index');
    route::post('/pengaturan-akun/tambah-akun', 'ControllerTambahAkun@tambah');
    route::get('/pengaturan-akun/edit/{id}', 'ControllerTambahAkun@getData');
    route::post('/pengaturan-akun/edit/{id}', 'ControllerTambahAkun@editData');
    route::get('/pengaturan-akun/hapus/{id}', 'ControllerTambahAkun@hapus');

    //setting
    route::get('/setting', 'ControllerSetting@index');
});

