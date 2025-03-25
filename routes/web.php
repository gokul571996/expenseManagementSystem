<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\expenseController;
use App\Http\Controllers\reportController;

Route::get('/', [mainController::class, 'index']);
Route::get('/login', [loginController::class, 'loginMain'])->name('login');
Route::post('/login', [loginController::class, 'login']);
Route::post('/register', [loginController::class, 'register'])->name('register');
Route::get('/dashboard', [mainController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/logout', [loginController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/expense/category', [categoryController::class, 'expenseCategory']);
    Route::delete('/category/{id}', [categoryController::class, 'categoryDelete']);
    Route::post('/category/save', [categoryController::class, 'categorySave']);
    Route::get('/profile', [UsersController::class, 'profilePage']);
    Route::post('/updateProfile', [UsersController::class, 'updateProfile']);
    Route::post('/category/update/{id}', [categoryController::class, 'updateCategory']);
    Route::get('/addExpense', [expenseController::class, 'addExpense']);
    Route::post('/expenses/save', [expenseController::class, 'expenseSave']);
    Route::get('/expense/allExpenses', [expenseController::class, 'allExpense']);
    Route::delete('/allExpense/{id}', [expenseController::class, 'expenseDelete']);
    Route::get('/expense/reports', [reportController::class, 'reports']);
    Route::get('/download-expense-pdf', [reportController::class, 'downloadExpensePdf']);
    Route::get('/download-expense-pdf-all', [reportController::class, 'downloadAllExpensesPdf']);
});
