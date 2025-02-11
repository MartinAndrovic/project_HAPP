<?php

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
    return view('main');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/auth/facebook/redirect', [\App\Http\Controllers\Auth\FacebookLoginController::class, 'redirect']);
Route::get('/auth/facebook/callback', [\App\Http\Controllers\Auth\FacebookLoginController::class, 'callback']);
Route::get('/admin_panel',[\App\Http\Controllers\AdminController::class,'index'])->name('name');


Route::get('/AP/users', [\App\Http\Controllers\AdminController::class,'users']);
Route::get('/AP/regions', [\App\Http\Controllers\AdminController::class,'regions']);
Route::get('/AP/districts', [\App\Http\Controllers\AdminController::class,'districts']);
Route::get('/AP/categories', [\App\Http\Controllers\AdminController::class,'categories']);
Route::get('/AP/activities', [\App\Http\Controllers\AdminController::class,'activities']);

Route::delete('/AP/deleteUser/{id}', [\App\Http\Controllers\AdminController::class,'deleteUser']);
Route::delete('/AP/deleteRegions/{id}', [\App\Http\Controllers\AdminController::class,'deleteRegion']);
Route::delete('/AP/deleteDistrict/{id}', [\App\Http\Controllers\AdminController::class,'deleteDistrict']);
Route::delete('/AP/deleteCategory/{id}', [\App\Http\Controllers\AdminController::class,'deleteCategory']);
Route::delete('/AP/deleteActivity/{id}', [\App\Http\Controllers\AdminController::class,'deleteActivity']);

Route::post('AP/addRegion',[\App\Http\Controllers\AdminController::class,'addRegion']);
Route::post('AP/addDistrict',[\App\Http\Controllers\AdminController::class,'addDistrict']);
Route::post('AP/addCategory',[\App\Http\Controllers\AdminController::class,'addCategory']);
Route::post('AP/addActivity',[\App\Http\Controllers\AdminController::class,'addActivity']);

Route::post('AP/updateUserName',[\App\Http\Controllers\AdminController::class,'updateUserName']);
Route::post('AP/updateRegionName',[\App\Http\Controllers\AdminController::class,'updateRegionName']);
Route::post('AP/updateDistrictName',[\App\Http\Controllers\AdminController::class,'updateDistrictName']);
Route::post('AP/updateCategoryName',[\App\Http\Controllers\AdminController::class,'updateCategoryName']);
Route::post('AP/updateActivityName',[\App\Http\Controllers\AdminController::class,'updateActivityName']);

Route::post('AP/addDistrictToRegion',[\App\Http\Controllers\AdminController::class,'addDistrictToRegion']);

Route::get('/home/notifications', [\App\Http\Controllers\NotificationController::class,'getNotifications']);

Route::get('/home/settings/{type}', [\App\Http\Controllers\HomeController::class,'settings']);
Route::get('/home/settings/get/personal', [\App\Http\Controllers\HomeController::class,'getPersonal']);
Route::get('/home/settings/get/filters', [\App\Http\Controllers\HomeController::class,'getFilters']);
Route::get('/home/settings/get/filter/{id}', [\App\Http\Controllers\HomeController::class,'getFilter']);
Route::post('/home/settings/savePersonal', [\App\Http\Controllers\HomeController::class,'savePersonal']);
Route::post('/home/settings/saveFilter',[\App\Http\Controllers\HomeController::class,'saveFilter']);
Route::post('/home/settings/updateFilter',[\App\Http\Controllers\HomeController::class,'updateFilter']);
Route::delete('home/settings/deleteFilter/{id}',[\App\Http\Controllers\HomeController::class,'deleteFilter']);

Route::get('/chat',[\App\Http\Controllers\MessageController::class,'index']);
Route::get('/chat/{id}',[\App\Http\Controllers\MessageController::class,'openChat']);
Route::post('/chat/send/{id}',[\App\Http\Controllers\MessageController::class,'save_send']);
Route::get('/home/matches',[\App\Http\Controllers\FilterMatchController::class,'getUsersMatches']);
Route::get('/home/messages/{id}',[\App\Http\Controllers\MessageController::class,'getUsersMatches']);
