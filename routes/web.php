<?php

use App\Http\Controllers\Api\ApisController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\City\CityController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\Notification\NotificationsController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Version\VersionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //DashboardController
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.home');
    //UserController
    Route::get('/user', [UserController::class, 'index'])->name('admin.user');
    Route::post('/user-made', [UserController::class, 'usermade'])->name('admin.made.user');
    Route::post('/user-delete/{id}', [UserController::class, 'deleteuser'])->name('delete.user');
    //CategoryController
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/add-cetegory', [CategoryController::class, 'addCetegory'])->name('admin.add.cetegory');
    Route::post('/cetegory-delete/{id}', [CategoryController::class, 'cetegoryDelete'])->name('admin.delete.cetegory');
    Route::get('/hit-api/{title}', [CategoryController::class, 'hitApi'])->name('admin.hit.api');
    //NewsController
    Route::get('/news', [NewsController::class, 'index'])->name('admin.news');
    Route::post('/add-news', [NewsController::class, 'addNews'])->name('admin.add.news');
    Route::post('/news-delete/{id}', [NewsController::class, 'deleteNews'])->name('delete.news');
    //NotificationsController
    Route::get('/notification', [NotificationsController::class, 'index'])->name('admin.notification');
    Route::post('/notification-send', [NotificationsController::class, 'notificationSend'])->name('admin.send.notification');
    //Route::get('/admin-notification', [NotificationsController::class, 'notification'])->name('admin.notifications');
    Route::get('/notify-news/{news}', [NotificationsController::class, 'notificationGive'])->name('admin.notify.news');
    //VersionsController
    Route::get('/version-update', [VersionsController::class, 'index'])->name('admin.version.update');
    Route::post('/version-delete/{id}', [VersionsController::class, 'deleteVersion'])->name('delete.version');
    //ApisController
    // Route::get('/api-select', [ApisController::class, 'index'])->name('admin.api');
    Route::get('/api', [ApisController::class, 'index'])->name('admin.api');
    Route::post('/api-create', [ApisController::class, 'apiCreate'])->name('admin.api.create');
    Route::post('/api-update/{id}', [ApisController::class, 'apiUpdate'])->name('admin.api.update');
    //CityController
    Route::get('/city', [CityController::class, 'index'])->name('admin.city');
    Route::post('/city-add', [CityController::class, 'cityAdd'])->name('admin.city.add');


    //Enable API Route
    // In your routes/web.php file
    Route::post('/enable-api/{id}', [ApisController::class, 'enable_api'])->name('enable.api');
    Route::post('/api-delete/{id}', [ApisController::class, 'delete_api'])->name('delete.api');
});

require __DIR__ . '/auth.php';