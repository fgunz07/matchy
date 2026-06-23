<?php

namespace App\Http\Controllers\API\User;

use App\Actions\User\UpdateUserAction;
use App\Data\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\{UserCollection, UserResource};
use App\Http\Responses\ResponsePaginated;
use App\Models\User;
use Illuminate\Http\Request;
use App\Queries\UserIndexQuery;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware(HandlePrecognitiveRequests::class, ['update'])]
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserIndexQuery $query)
    {
        return new ResponsePaginated(
            new UserCollection(
                $query->paginate(
                    $request->input('per_page', 15)
                )
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource(
            $user->loadCount('likers')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action)
    {
        return new UserResource(
            $action->execute(
                $user,
                UserData::from(
                    $request->validated()
                )
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
