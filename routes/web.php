<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Categories\CategoryIndex;
use App\Livewire\Categories\CategoryReport;
use App\Livewire\Dashboard;
use App\Livewire\Expenses\ExpenseForm;
use App\Livewire\Expenses\ExpenseList;
use App\Livewire\Migration\PullFromServer;
use App\Livewire\Reports\Reports;
use App\Http\Controllers\ServerMigrationExportController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Server-to-server migration (public, secret-gated — see MigrationImportService)
Route::get('/server-migration', PullFromServer::class)->name('server-migration');
Route::get('/server-migration/export', ServerMigrationExportController::class)
    ->middleware('throttle:10,1')
    ->name('server-migration.export');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/categories', CategoryIndex::class)->name('categories.index');
    Route::get('/categories/{mainCategory}/report', CategoryReport::class)->name('categories.report');
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
