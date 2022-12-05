<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/orders/create', [OrderController::class, 'create'])
    ->middleware('require.role:'.User::ClENT_ROLE)->name('orders.create');

Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('require.role:'.User::ClENT_ROLE)->name('orders.store');

Route::get('/orders', [OrderController::class, 'index'])
    ->middleware('require.role:'.User::MANAGER_ROLE)->name('orders.index');

Route::get('/orders/reply/{id}', [OrderController::class, 'reply'])
    ->middleware('require.role:'.User::MANAGER_ROLE)->name('orders.reply');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
