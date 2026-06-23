<?php

namespace App\Http\Controllers\API\Auth;

use App\Actions\Auth\ForgetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware(HandlePrecognitiveRequests::class, ['__invoke'])]
class ForgetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ForgetPasswordRequest $request, ForgetPasswordAction $action)
    {
        return $action->execute(
            $request->validated('email')
        );
    }
}
