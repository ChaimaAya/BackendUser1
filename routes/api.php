<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\PublicationsController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/getMessage/{id}', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>'api','prefix'=>'auth'],function($router){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/sendPasswordResetLink',[PasswordResetRequestController::class,'sendEmail']);

    // Route::post('/resetPassword',[ChangePasswordController::class,'passwordResetProcess']);
    Route::post('/reset-password', [PasswordResetController::class, 'reset']);





    Route::get('/user',[AuthController::class,'user']);
    Route::get('/userById/{userId}', [AuthController::class, 'userById']);
    Route::post('/ajouteTask',[CalendarController::class,'store']);
    Route::put('/modifierTache/{id}',[CalendarController::class,'updateTask']);
    Route::delete('/supprimerTask/{id}',[CalendarController::class,'destroy']);

    Route::get('/user',[AuthController::class,'user']);
    Route::get('/secteurs',[AuthController::class,'secteurs']);
    Route::put('/updateprofile',[AuthController::class,'updateProfile']);
    Route::get('/startup',[AuthController::class,'getStartupDetailsForUser']);
    Route::get('/startup/{id}',[AuthController::class,'getStartupDetailsForUserById']);
    Route::get('/getTasks',[CalendarController::class,'getTasks']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/getUserType',[AuthController::class,'getUserType']);
    Route::get('/getMessage/{id}', [CalendarController::class, 'getMessage']);
    //Routes Publication

    Route::get('/publications', [PublicationsController::class, 'index']);
    Route::post('/publication', [PublicationsController::class, 'store']);
    Route::get('/publicationsUser', [PublicationsController::class, 'userProfilePublications']);
    Route::get('/userProfilePublicationsId/{id}', [PublicationsController::class, 'userProfilePublicationsId']);
    Route::get('/publications/{id}', [PublicationsController::class, 'show']);
    Route::get('/publications/{id}/edit', [PublicationsController::class, 'edit']);
    Route::put('/publications/{id}/edit', [PublicationsController::class, 'update']);
    Route::delete('/publications/{id}/', [PublicationsController::class, 'destroy']);

    Route::post('/liked/{id}', [PublicationsController::class, 'like']);
    Route::put('/disliked/{id}', [PublicationsController::class, 'dislike']);


    Route::get('/search', [UserController::class, 'search']);
    Route::post('/uploadAvatar', [UserController::class, 'upload']);
    // Routes reclamations

    Route::post('/reclamation', [ReclamationController::class, 'store']);
    Route::get('/listReclamation', [ReclamationController::class, 'index']);
    Route::get('/detailReclamation/{id}', [ReclamationController::class, 'show']);


     // Routes notifications
     Route::get('/notifications',[NotificationController::class,'LikedNotifications']);
     Route::get('/markAllRead',[NotificationController::class,'markAsReadAll']);
     Route::get('/markAsRead/{id}',[NotificationController::class,'markAsRead']);
         Route::get('/countNotifications',[NotificationController::class,'countNotifications']);


         Route::post('/follow', [FollowController::class, 'follow']);
         Route::delete('/unfollow/{id}', [FollowController::class, 'unfollow']);
         Route::get('/checkFollow/{userId}', [FollowController::class, 'checkFollow']);
});
