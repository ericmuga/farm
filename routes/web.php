<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Services\ExportService;
use App\Http\Controllers\{FarmerController,
                          ContactController,
                          AssociateController,
                          FarmController,
                          LocationController,
                          MediumController};
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/',fn()=>Auth::check()?redirect('dashboard'):redirect('login'));
Route::middleware('auth')->group(function () {


Route::resource('farmer', FarmerController::class);
Route::get('exportFarmer',[FarmerController::class,'export'])->name('farmers.export');
Route::get('addMedia',[FarmerController::class,'addMedia'])->name('farmers.addMedia');


Route::resource('contact', ContactController::class);
Route::resource('associates', AssociateController::class);
Route::resource('locations', LocationController::class);
Route::get('exportLocation',[LocationController::class,'export'])->name('locations.export');
Route::resource('medium', MediumController::class);
Route::resource('farms', FarmController::class);
// Route::get('exports/{route}',[ExportService::class,'export']);
});




require __DIR__.'/auth.php';
