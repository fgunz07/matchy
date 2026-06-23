<?php

use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request, int $id) {
    $request->fulfill();

    return view('email-verified');
})
    ->middleware(['signed'])
    ->name('verification.verify');
