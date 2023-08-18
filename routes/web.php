<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php
Route::post('/time_records/check_in', [App\Http\Controllers\TimeRecordsController::class, 'checkIn'])->name('time_records.check_in');
Route::post('/time_records/check_out', [App\Http\Controllers\TimeRecordsController::class, 'checkOut'])->name('time_records.check_out');
Route::get('/time_records/show', [App\Http\Controllers\TimeRecordsController::class, 'show'])->name('time_records.show');
Route::get('/time_records/show', [App\Http\Controllers\TimeRecordsController::class, 'show'])->name('time_records.show');
Route::get('/shifts_input', [App\Http\Controllers\ShiftsController::class, 'create'])->name('shifts_input');
Route::post('/shifts_input/store', [App\Http\Controllers\ShiftsController::class, 'store'])->name('shifts_input.store');
Route::get('/shifts_input/show', [App\Http\Controllers\ShiftsController::class, 'show'])->name('shifts_input.show');
Route::post('/shifts_input/{id}', [App\Http\Controllers\ShiftsController::class, 'destroy'])->name('shifts_input.delete');
Route::get('/employee', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employee');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
