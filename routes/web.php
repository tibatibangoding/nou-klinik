<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApotekerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JenisObatController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KategoriObatController;
use App\Http\Controllers\KlinikController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PeriksaController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\TindakanController;
use App\Models\JenisObat;
use App\Models\KategoriObat;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create')->middleware('role:admin');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
    Route::get('/dashboard/showdokter/{id}', [DashboardController::class, 'showDokter'])->name('showDokter')->middleware('auth');
    Route::get('/fetchobat/{id}', [PenjualanController::class, 'fetchObat'])->name('fetchObat')->middleware('auth');
    Route::get('/penjualan-konfirm/{id}', [PenjualanController::class, 'konfirmasi'])->name('penjualan-konfirm')->middleware('auth');
    Route::put('/penjualan-konfirm/{id}', [PenjualanController::class, 'konfirmasiUpdate'])->name('penjualan-konfirm-update')->middleware('auth');
    Route::get('/dashboard/showapoteker/{id}', [DashboardController::class, 'showApoteker'])->name('showApoteker')->middleware('auth');
    Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'index'])->name('pendaftaran.index')->middleware(['role:admin|dokter']);
    Route::get('/cekantrian', [PendaftaranController::class, 'cekantrian'])->name('cekantrian')->middleware(['role:admin|dokter']);
    Route::get('/live-antrian', [PendaftaranController::class, 'live'])->name('live')->middleware(['role:admin|dokter']);
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->middleware(['role:admin'])->name('pendaftaran.store');
    Route::get('/pendaftaran-all', [PendaftaranController::class, 'all'])->name('pendaftaran.all')->middleware(['role:admin|dokter']);
    Route::resource('poli', PoliController::class)->middleware(['role:admin']);
    Route::resource('dokter', DokterController::class)->middleware(['role:admin']);
    Route::resource('obat', ObatController::class)->middleware(['role:admin|apoteker']);
    Route::resource('jadwal', JadwalController::class)->middleware(['role:admin']);
    Route::resource('admin', AdminController::class)->middleware(['role:admin']);
    Route::resource('apoteker', ApotekerController::class)->middleware(['role:admin']);
    Route::resource('pasien', PasienController::class)->middleware(['role:dokter|admin']);
    Route::resource('tindakan', TindakanController::class)->middleware(['role:admin']);
    Route::get('dataTablesTindakan', [TindakanController::class, 'dataTablesTindakan'])->middleware(['role:admin'])->name('dataTablesTindakan');
    Route::get('/resep', [ResepController::class, 'index'])->middleware(['role:apoteker'])->name('resep.index');
    Route::get('/resep/detail/{id}', [ResepController::class, 'show'])->middleware(['role:apoteker'])->name('resep.show');
    Route::get('/resep/edit/{id}', [ResepController::class, 'edit'])->middleware('role:apoteker')->name('resep.edit');
    Route::put('/resep/edit/{id}', [ResepController::class, 'update'])->middleware('role:apoteker')->name('resep.update');
    Route::get('/periksa', [PeriksaController::class, 'index'])->middleware('role:dokter')->name('periksa.index');
    Route::get('/periksa/edit/{id}', [PeriksaController::class, 'edit'])->middleware('role:admin')->name('periksa.edit');
    Route::get('/periksa/exports', [PeriksaController::class, 'export'])->middleware('role:admin')->name('periksa.export');
    Route::get('/klinik/edit', [KlinikController::class, 'index'])->middleware('role:admin')->name('klinik.index');
    Route::put('/klinik/update', [KlinikController::class, 'klinikUpdate'])->middleware('role:admin')->name('klinik.update');
    Route::get('/pasien/exports/{id}', [PeriksaController::class, 'exportPasien'])->middleware('role:admin')->name('pasien.export');
    Route::post('/obat/import', [ObatController::class, 'importExcel'])->middleware('role:admin')->name('obat.import');
    Route::post('/tindakan/import', [TindakanController::class, 'importExcel'])->middleware('role:admin')->name('tindakan.import');
    Route::put('/periksa/update/{id}', [PeriksaController::class, 'update'])->middleware('role:admin')->name('periksa.update');
    Route::get('/periksa/create/{id}', [PeriksaController::class, 'create'])->middleware('role:dokter')->name('periksa.create');
    Route::post('/periksa/create', [PeriksaController::class, 'store'])->middleware('role:dokter')->name('periksa.store');
    Route::delete('/periksa/delete/{id}', [PeriksaController::class, 'destroy'])->middleware('role:dokter')->name('periksa.destroy');
    Route::get('/pembayaran/create/{id}', [PembayaranController::class, 'create'])->middleware('role:apoteker')->name('pembayaran.create');
    Route::post('/pembayaran/create', [PembayaranController::class, 'store'])->middleware('role:apoteker')->name('pembayaran.store');
    Route::get('/laporan-pemeriksaan', [LaporanController::class, 'index'])->middleware('role:admin')->name('laporan.index');
    Route::get('/laporan-penjualan', [LaporanController::class, 'penjualan'])->middleware(['role:apoteker|admin'])->name('laporan.penjualan');
    Route::resource('penjualan', PenjualanController::class)->middleware(['role:apoteker|admin']);
    Route::get('cetak-penjualan/{id}', [PenjualanController::class, 'invoice'])->middleware(['role:apoteker|admin'])->name('cetak_penjualan');
    Route::get('cetak-pembayaran/{id}', [PembayaranController::class, 'invoice'])->middleware(['role:apoteker|admin'])->name('cetak_pembayaran');
    Route::get('penjualan-sukses/{id}', [PenjualanController::class, 'sukses'])->middleware(['role:apoteker|admin'])->name('penjualan-sukses');
    Route::get('pembayaran-sukses/{id}', [PembayaranController::class, 'sukses'])->middleware(['role:apoteker'])->name('pembayaran-sukses');
    Route::resource('jenis', JenisObatController::class)->middleware(['role:apoteker|admin']);
    Route::resource('kategori', KategoriObatController::class)->middleware(['role:apoteker|admin']);
    Route::resource('surgery', SurgeryController::class)->middleware(['role:dokter']);
    Route::get('pembayaran/all-pembayaran', [PembayaranController::class, 'all'])->middleware(['role:admin|apoteker'])->name('pembayaran.all');
    Route::get('pasien-all', [PasienController::class, 'all'])->middleware(['role:dokter|admin'])->name('pasien.all');
    Route::get('showAll/{id}', [PasienController::class, 'showAll'])->middleware(['role:dokter|admin'])->name('pasien.showAll');
    Route::get('pembayaran/{id}', [PembayaranController::class, 'show'])->middleware(['role:admin|apoteker'])->name('pembayaran.show');
    Route::get('dataTablesPendaftaran', [DashboardController::class, 'dataTablesPendaftaran'])->middleware('auth')->name('dataTablesPendaftaran');
    Route::get('dataTablesTransaksi', [DashboardController::class, 'dataTablesTransaksi'])->middleware('auth')->name('dataTablesTransaksi');
    Route::get('dataTablesPasien', [DashboardController::class, 'dataTablesPasien'])->middleware('auth')->name('dataTablesPasien');
    Route::get('dataTablesResep', [DashboardController::class, 'dataTablesResep'])->middleware('auth')->name('dataTablesResep');
    Route::get('dataTablesPoli', [PoliController::class, 'dataTablesPoli'])->middleware('auth')->name('dataTablesPoli');
    Route::get('dataTablesDokter', [DokterController::class, 'dataTablesDokter'])->middleware('auth')->name('dataTablesDokter');
    Route::get('dataTablesApoteker', [ApotekerController::class, 'dataTablesApoteker'])->middleware('auth')->name('dataTablesApoteker');
    Route::get('dataTablesAdmin', [AdminController::class, 'dataTablesAdmin'])->middleware('auth')->name('dataTablesAdmin');
    Route::get('dataTablesObat', [ObatController::class, 'dataTablesObat'])->middleware('auth')->name('dataTablesObat');
    Route::get('dataTablesPendaftaranAll', [DashboardController::class, 'dataTablesPendaftaranAll'])->middleware('auth')->name('dataTablesPendaftaranAll');
    Route::get('dataTablesJenis', [JenisObatController::class, 'dataTablesJenis'])->middleware('auth')->name('dataTablesJenis');
    Route::get('dataTablesKategori', [KategoriObatController::class, 'dataTablesKategori'])->middleware('auth')->name('dataTablesKategori');
    Route::get('dataTablesPembayaran', [PembayaranController::class, 'dataTablesPembayaran'])->middleware('auth')->name('dataTablesPembayaran');
    Route::get('dataTablesRekamMedis/{id}', [PasienController::class, 'dataTablesRekamMedis'])->middleware('auth')->name('dataTablesRekamMedis');
    Route::get('pasien/rekamMedis/{id}', [PasienController::class, 'rekamMedis'])->middleware('auth')->name('rekamMedis');
    Route::get('apipasien/{id}', [PasienController::class, 'apiPasien'])->middleware('auth')->name('apiPasien');
    Route::get('createPoliDokter', [PoliController::class, 'createPoliDokter'])->name('createPoliDokter');
    Route::post('storeDokterPoli', [PoliController::class, 'storeDokter'])->name('storeDokterPoli');
    Route::get('createJenisObat', [JenisObatController::class, 'createJenisObat'])->name('createJenisObat');
    Route::post('storeJenisObat', [JenisObatController::class, 'storeJenis'])->name('storeJenisObat');
    Route::get('showPasienBelum/{id}', [PasienController::class, 'showPasienBelum'])->name('showPasienBelum');
    Route::get('createKategoriObat', [KategoriObatController::class, 'createKategoriObat'])->name('createKategoriObat');
    Route::post('storeKategoriObat', [KategoriObatController::class, 'storeKategori'])->name('storeKategoriObat');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
