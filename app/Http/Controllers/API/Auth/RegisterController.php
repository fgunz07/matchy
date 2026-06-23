<?php

namespace App\Http\Controllers\API\Auth;

use App\Actions\Auth\RegisterAction;
use App\Data\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware(HandlePrecognitiveRequests::class, ['__invoke'])]
class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request, RegisterAction $action)
    {
        return new UserResource(
            $action->execute(
                UserData::from(
                    $request->validated()
                )
            )
        );
    }
}
