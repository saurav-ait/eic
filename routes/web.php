<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\JobController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/services', [UserController::class, 'services'])->name('services');
Route::get('/work-visa', [UserController::class, 'workVisa'])->name('work-visa');
Route::get('/drivers', [UserController::class, 'drivers'])->name('drivers');
Route::get('/countries', [UserController::class, 'countries'])->name('countries');
Route::get('/jobs', [UserController::class, 'jobs'])->name('jobs');
Route::get('/contact', [UserController::class, 'contact'])->name('contact');
Route::get('/assessment', [UserController::class, 'assessment'])->name('assessment');

Route::get('/light-vehicle-driver', [UserController::class, 'lightVehicleDriver'])->name('light-vehicle-driver');
Route::get('/ahmed-videos', [UserController::class, 'ahmedVideos'])->name('ahmed-videos');


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['guestOnly'])->group(function () {

    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'auth_login'])->name('auth-login');

    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::post('/register', [UserController::class, 'auth_register'])->name('auth-register');

});


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['checkLogin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {

        /*
        |---------------- USER MANAGEMENT ----------------|
        */
        Route::get('/manage-users', [DashboardController::class, 'manageUsers'])->name('manage-users');
        Route::post('/update-role/{user}', [DashboardController::class, 'updateRole'])->name('update.role');


        /*
        |---------------- PASSPORT ----------------|
        */
        Route::get('passports', [PassportController::class, 'index'])->name('passports.index');
        Route::get('passports/create', [PassportController::class, 'create'])->name('passports.create');
        Route::post('passports', [PassportController::class, 'store'])->name('passports.store');
        Route::get('passports/{passport}', [PassportController::class, 'show'])->name('passports.show');
        Route::post('/passports/{passport}/delete', [App\Http\Controllers\PassportController::class, 'destroy'])
        ->name('passports.destroy');

        /*
        |---------------- DOCUMENTS ----------------|
        */
        Route::get('passports/{passport}/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('passports/{passport}/documents', [DocumentController::class, 'store'])->name('documents.store');

        // ✅ FIXED DELETE (IMPORTANT)
        Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');


        /*
        |---------------- VIDEOS ----------------|
        */
        Route::get('passports/{passport}/videos/create', [VideoController::class, 'create'])->name('videos.create');
        Route::post('passports/{passport}/videos', [VideoController::class, 'store'])->name('videos.store');

        // ✅ FIXED DELETE
        Route::delete('videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');


        /*
        |---------------- JOB MANAGEMENT ----------------|
        */

        // Dashboard (optional overview)
        Route::get('/jobs/manage', [JobController::class, 'index'])->name('jobs.manage');

        // ✅ CATEGORY PAGE
        Route::get('/jobs/categories', function () {
            $categories = \App\Models\JobCategory::with('subcategories')->get();
            return view('client.jobs.categories', compact('categories'));
        })->name('jobs.categories');

        // ✅ ASSIGN PAGE
        Route::get('/jobs/assign', function () {
            $categories = \App\Models\JobCategory::with('subcategories.passports')->get();
            $passports = \App\Models\Passport::all();
            return view('client.jobs.assign', compact('categories', 'passports'));
        })->name('jobs.assign');

        Route::post('/jobs/unassign-passport/{id}', [JobController::class, 'unassignPassport'])
        ->name('jobs.unassign.passport');

        // STATUS
        Route::get('/jobs/status', [JobController::class, 'statusIndex'])->name('jobs.status');
        Route::post('/jobs/status', [JobController::class, 'storeStatus'])->name('jobs.status.store');
        Route::post('/jobs/status/update/{id}', [JobController::class, 'updateStatus'])->name('jobs.status.update');
        Route::post('/jobs/status/delete/{id}', [JobController::class, 'deleteStatus'])->name('jobs.status.delete');

        // PASSPORT STATUS
        Route::get('/jobs/passport-status', [JobController::class, 'passportStatus'])->name('jobs.passport.status');
        Route::post('/jobs/passport-status', [JobController::class, 'updatePassportStatus'])->name('jobs.passport.status.update');
        // Services
        Route::get('/jobs/services', [JobController::class, 'services'])->name('jobs.services');

        /*
        |---------------- CATEGORY ACTIONS ----------------|
        */
        Route::post('/jobs/category', [JobController::class, 'storeCategory'])->name('jobs.category.store');
        Route::post('/jobs/category/update/{id}', [JobController::class, 'updateCategory'])->name('jobs.category.update');
        Route::post('/jobs/category/delete/{id}', [JobController::class, 'deleteCategory'])->name('jobs.category.delete');


        /*
        |---------------- SUBCATEGORY ACTIONS ----------------|
        */
        Route::post('/jobs/subcategory', [JobController::class, 'storeSubcategory'])->name('jobs.subcategory.store');
        Route::post('/jobs/subcategory/delete/{id}', [JobController::class, 'deleteSubcategory'])->name('jobs.subcategory.delete');


        /*
        |---------------- PASSPORT ASSIGN ----------------|
        */
        Route::post('/jobs/assign-passport', [JobController::class, 'assignPassport'])->name('jobs.assign.passport');

    });

});


/*
|--------------------------------------------------------------------------
| STATIC PAGES
|--------------------------------------------------------------------------
*/

Route::get('/terms', [UserController::class, 'register'])->name('terms');
Route::get('/privacy', [UserController::class, 'register'])->name('privacy');