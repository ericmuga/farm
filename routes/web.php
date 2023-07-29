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
                          MediumController,
                          VisitController};
use App\Models\{Farmer,User};
use Carbon\Carbon;
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

    $farmers=Farmer::with('farms','farms.location','farms.visits')->orderBy('id')->get();
    $lastUpdated=$farmers->first()?Carbon::parse($farmers->first()->updated_at)->diffForHumans():'';
    $users=User::select('name','id')->get();
    $registeredBy=User::select('name')->withCount('farmers')->get();
    $data=['farmers'=>$farmers,'lastUpdated'=>$lastUpdated];
    $farmerData=compact('farmers','lastUpdated','registeredBy');

    return Inertia::render('Dashboard',compact('farmerData'));
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
Route::resource('visits', VisitController::class);
Route::get('exportVisit',[VisitController::class,'export'])->name('visits.export');
// Route::get('exports/{route}',[ExportService::class,'export']);
});




require __DIR__.'/auth.php';
