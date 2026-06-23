<?php

use App\Http\Controllers\API\Auth\Google\{
    GoogleCallbackController,
    GoogleController
};
use App\Http\Controllers\API\Auth\{
    LoginController, 
    LogoutController, 
    RegisterController, 
    ResetPasswordController, 
    ForgetPasswordController
};
use App\Http\Controllers\API\{
    ConversationController,
    MeController,
    MessageController
};
use App\Http\Controllers\API\User\{
    UnlikeUserController,
    LikeUserController,
    UserController
};
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->as('auth.')
    ->group(function () {
        Route::prefix('social')
            ->as('social.')
            ->group(function () {
                Route::prefix('google')
                    ->as('google.')
                    ->group(function () {
                        Route::get('', GoogleController::class)
                            ->name('auth');
                        Route::get('callback', GoogleCallbackController::class)
                            ->name('callback');
                    });
            });
        Route::post('forget-password', ForgetPasswordController::class)
            ->name('forget-password');
        Route::post('reset-password', ResetPasswordController::class)
            ->name('reset-password');
        Route::post('login', LoginController::class);
        Route::post('register', RegisterController::class);
        Route::post('logout', LogoutController::class)
            ->middleware('auth:sanctum')
            ->name('logout');
    });

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('me', MeController::class)
            ->name('me');
        Route::prefix('users')
            ->as('users.')
            ->group(function () {
                Route::apiResource('', UserController::class)
                    ->except(['store', 'destroy'])
                    ->parameters(['' => 'user']);
                Route::post('{user}/like', LikeUserController::class)
                    ->name('like');
                Route::post('{user}/unlike', UnlikeUserController::class)
                    ->name('unlike');
            });
        Route::apiResource('users.conversations', ConversationController::class)
            ->only(['index']);
        Route::apiResource('conversations', ConversationController::class)
            ->except(['index', 'update']);
        Route::apiResource('conversations.messages', MessageController::class)
            ->only(['store', 'index']);
    });
