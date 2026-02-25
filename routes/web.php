<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('pocetna');

Route::middleware('guest')->group(function () {
    Route::get('/registracija', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registracija', [AuthController::class, 'register'])->name('register.store');

    Route::get('/prijava', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/prijava', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/odjava', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/moj-nalog', [AccountController::class, 'index'])->name('account.index')->middleware('auth');

Route::get('/kalkulator', [CalculatorController::class, 'index'])->name('kalkulator');
Route::get('/kalkulator/izracunaj', static fn () => redirect()->route('kalkulator'))->name('kalkulator.izracunaj.get');
Route::post('/kalkulator/izracunaj', [CalculatorController::class, 'calculate'])->name('kalkulator.izracunaj');
Route::post('/kalkulator/sacuvaj', [CalculatorController::class, 'saveCalculation'])->name('kalkulator.sacuvaj');
Route::get('/kalkulator/pdf', [CalculatorController::class, 'downloadPdf'])->name('kalkulator.pdf');
