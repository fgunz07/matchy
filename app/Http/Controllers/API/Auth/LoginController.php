<?php

namespace App\Http\Controllers\API\Auth;

use App\Actions\Auth\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware(HandlePrecognitiveRequests::class, ['__invoke'])]
class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request, LoginAction $action)
    {
        return new JsonResource(
            $action->execute(
                email: $request->validated('email'),
                password: $request->validated('password')
            )
        );
    }
}
