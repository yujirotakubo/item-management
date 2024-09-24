<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WithdrawHistoryController;
use App\Http\Controllers\UserController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'dashboardIndex'])->name('home');


Route::prefix('items')->group(function () {
    Route::get('/', [App\Http\Controllers\ItemController::class, 'index']);
    Route::get('/add', [App\Http\Controllers\ItemController::class, 'add']);
    Route::post('/add', [App\Http\Controllers\ItemController::class, 'add']);
    Route::get('/edit/{id}', [App\Http\Controllers\ItemController::class, 'edit']);
    Route::post('/update/{id}', [App\Http\Controllers\ItemController::class, 'update']);
    Route::delete('/delete/{id}', [App\Http\Controllers\ItemController::class, 'delete']);
    Route::post('withdraw/{id}', [App\Http\Controllers\ItemController::class, 'withdraw']);
    Route::get('/export', [App\Http\Controllers\ItemController::class, 'exportCsv'])->name('items.export');
    Route::get('/export/history', [App\Http\Controllers\ItemController::class, 'exportHistory'])->name('items.export.history');
});

Route::get('withdraw-histories', [App\Http\Controllers\WithdrawHistoryController::class, 'index'])->name('withdraw.histories');
Route::get('withdraw-histories/{id}/pdf', [App\Http\Controllers\WithdrawHistoryController::class, 'exportPDF'])->name('withdraw-history.pdf');

Route::get('/send-test-email', [App\Http\Controllers\UserController::class, 'sendTestEmail'])->middleware('auth');
