<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Categories\CategoryIndex;
use App\Livewire\Dashboard;
use App\Livewire\Expenses\ExpenseForm;
use App\Livewire\Expenses\ExpenseList;
use App\Livewire\Reports\Reports;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/categories', CategoryIndex::class)->name('categories.index');
    Route::get('/expenses', ExpenseList::class)->name('expenses.index');
    Route::get('/expenses/create', ExpenseForm::class)->name('expenses.create');
    Route::get('/expenses/{expense}/edit', ExpenseForm::class)->name('expenses.edit');
    Route::get('/reports', Reports::class)->name('reports');

    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
