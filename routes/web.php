<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminPDFController;
use App\Http\Controllers\AdminObatController;
use App\Http\Controllers\AdminMedisController;
use App\Http\Controllers\AdminPasienController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminDashboardController;

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

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/logout', function () {
    return redirect('/');
});

// dashboard admin
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware('admin');

// dashboard admin pasien
Route::resource('/admin/pasien', AdminPasienController::class)->middleware('admin');
Route::post('/admin/pasien/delete', [AdminPasienController::class, 'deletePatient'])->middleware('admin');
Route::get('/admin/pasien/delete', function () {
    return redirect('/admin/pasien');
})->middleware('admin');
Route::post(
    '/admin/pasien/filters',
    [AdminPasienController::class, 'filters']
)->name('pasien.filters')->middleware('admin');

Route::get('/pasien/download-pdf', [AdminPasienController::class, 'downloadPDF'])->name('pasien.download_pdf')->middleware('admin');
// Route::post('/admin/pasien/edit', [AdminPasienController::class, 'editPatient'])->middleware('admin');
// Route::get('/admin/pasien/edit', function () {
//     return redirect('/admin/pasien');
// })->middleware('admin');
// Route::get('/admin/pasien/tambah', [AdminPasienController::class, 'create'])->middleware('admin');



// dashboard admin obat
Route::resource('/admin/obat', AdminObatController::class)->middleware('admin');
Route::post('/admin/obat/delete', [AdminObatController::class, 'deleteObat'])->middleware('admin');
Route::get('/admin/obat/delete', function () {
    return redirect('/admin/obat');
})->middleware('admin');
Route::post('/obat/tambah-stok', [AdminObatController::class, 'tambahStok'])->name('obat.tambahStok')->middleware('admin');
Route::get(
    '/cari-obat',
    [AdminObatController::class, 'cariObat']
)->name('cari.obat')->middleware('admin');
Route::Post('/admin/obat/filters', [AdminObatController::class, 'filters'])->name('obat.filters')->middleware('admin');
Route::get('/obat/download-pdf', [AdminObatController::class, 'downloadPDF'])->name('obat.download_pdf')->middleware('admin');


// dashboard admin rekam medis
Route::resource('/admin/medis', AdminMedisController::class)->middleware('admin');
Route::get('/search-patient', [AdminMedisController::class, 'searchPatient'])->middleware('admin');
Route::get('/search-prescription', [AdminMedisController::class, 'searchPrescription'])->middleware('admin');
Route::Post('/admin/medis/filters', [AdminMedisController::class, 'filters'])->name('medis.filters')->middleware('admin');
Route::get('/medis/download-pdf', [AdminMedisController::class, 'downloadPDF'])->name('medis.download_pdf')->middleware('admin');




// dashboard admin pengaturan
Route::get('/admin/pengaturan', [AdminSettingController::class, 'index'])->middleware('admin');


//pdf
// Route::get('/admin/pasien/generate-pdf', [AdminPDFController::class, 'generatePDF'])->name('admin.pdf.generate')->middleware();
