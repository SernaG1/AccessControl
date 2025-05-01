<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersIncomeController;
use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    if (auth('admin')->check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Página de inicio protegida
Route::get('/home', [HomeController::class, 'index'])->middleware('auth:admin')->name('home');



// Rutas públicas
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas por login
Route::middleware('auth')->group(function () {
    //rutas del search
    Route::get('/search', [UsersIncomeController::class, 'getAllUsers'])->name('incomes.search');
    Route::get('/incomes/{usersIncome}/edit', [UsersIncomeController::class, 'edit'])->name('incomes.edit');
    Route::put('/incomes/{usersIncome}', [UsersIncomeController::class, 'update'])->name('incomes.update');
    Route::delete('/incomes/{usersIncome}', [UsersIncomeController::class, 'destroy'])->name('incomes.destroy');
//otras rutas 
    Route::get('/incomes/{id}', [UsersIncomeController::class, 'show'])->name('incomes.show');
    Route::get('/create', [UsersIncomeController::class, 'create'])->name('incomes.create');
    Route::get('/incomes', [AccessLogController::class, 'dashboard'])->name('incomes.index');
    Route::post('/incomes', [UsersIncomeController::class, 'store'])->name('incomes.store');

    Route::prefix('visitante')->group(function () {
        Route::post('entrada/{visitorId}', [AccessLogController::class, 'registerEntry'])->name('visitante.entrada');
        Route::post('salida/{visitorId}', [AccessLogController::class, 'registerExit'])->name('visitante.salida');
        Route::get('visitantes-dentro', [AccessLogController::class, 'getVisitorsInside'])->name('visitante.dentro');
    });
});
