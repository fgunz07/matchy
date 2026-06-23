<?php

namespace App\Http\Controllers\API\Auth\Google;

use App\Actions\Auth\GoogleCallbackAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, GoogleCallbackAction $googleCallbackAction)
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        return redirect()->away($googleCallbackAction->execute($googleUser));
    }
}
