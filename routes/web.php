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

Route::get('hash-make', function() {
    return Hash::make("123123");
});


Auth::routes();

Route::get('/', [App\Http\Controllers\LandingpageController::class, 'index'])->name('index');

Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::post('/auth-login', [App\Http\Controllers\LoginController::class, 'login'])->name('auth-login');

Route::get('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register');
Route::post('/register/storeRegist', [App\Http\Controllers\LoginController::class, 'storeRegist'])->name('storeRegist');

Route::get('logout', function() {

    Auth::logout();

    return redirect('/login');
});

Route::middleware(['web'])->group(function() {

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboardIndex'])->name('dashboard');

    Route::get('/jobfield', [App\Http\Controllers\jobfieldController::class, 'indexJobfield'])->name('jobfield');
    Route::get('/jobfield/getData', [App\Http\Controllers\jobfieldController::class, 'getData']);
    Route::post('/jobfield/createData', [App\Http\Controllers\jobfieldController::class, 'createData']);
    Route::post('/jobfield/deleteData/{id}', [App\Http\Controllers\jobfieldController::class, 'deleteData']);
    Route::post('/jobfield/updateData/{id}', [App\Http\Controllers\jobfieldController::class, 'updateData']);

    Route::get('/jobs', [App\Http\Controllers\JobsController::class, 'index'])->name('jobs');
    Route::get('/jobs/getData', [App\Http\Controllers\JobsController::class, 'getData'])->name('getData');
    Route::post('/jobs/createData/', [App\Http\Controllers\JobsController::class, 'createData'])->name('createData');
    Route::post('/jobs/deleteData/{id}', [App\Http\Controllers\JobsController::class, 'deleteData'])->name('deleteData');
    Route::post('/jobs/updateData/{id}', [App\Http\Controllers\JobsController::class, 'updateData'])->name('updateData');


});

Route::middleware(['CheckLevel: ADMIN'])->group(function() {

    Route::get('/trash', [App\Http\Controllers\JobsController::class, 'trash'])->name('trash');
    Route::get('jobs/getDataTrash', [App\Http\Controllers\JobsController::class, 'getDataTrash']);
    Route::post('jobs/deletePermanent/{id}', [App\Http\Controllers\JobsController::class, 'deletePermanentData']);
    Route::get('jobs/restoreData/{id}', [App\Http\Controllers\JobsController::class, 'restoreData']);

});
