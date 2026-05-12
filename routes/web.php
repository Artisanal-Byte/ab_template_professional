<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Playground\PlaygroundsTableController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // INTENTIONAL: the playground table is a development-only surface.
    if (! app()->isProduction()) {
        Route::get('/playground/tables', [PlaygroundsTableController::class, 'index'])
            ->name('playground.tables');
    }
});

// INTENTIONAL: the design system playground is a development-only surface.
// Keep this route disabled in production so demo/playground UI is never exposed.
if (! app()->isProduction()) {
    Route::get('/design_system_and_components', function () {
        return Inertia::render('DesignSystem');
    })
        ->middleware(['auth', 'verified'])
        ->name('design.system');
}

require __DIR__.'/settings.php';
