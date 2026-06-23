<?php

namespace App\Http\Controllers\API;

use App\Actions\Message\StoreMessageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Resources\Message\{MessageCollection, MessageResource};
use App\Http\Responses\ResponsePaginated;
use App\Models\Conversation;
use App\Queries\MessageIndexQuery;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware(HandlePrecognitiveRequests::class, ['store'])]
class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Conversation $conversation, MessageIndexQuery $query)
     {
        return new ResponsePaginated(
            new MessageCollection(
                $query->paginate(
                    perPage: request()->input('per_page', 15)
                )
            )
        );
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request, Conversation $conversation, StoreMessageAction $action)
    {
        return new MessageResource(
            $action->execute(
                $request->user(),
                $conversation,
                $request->validated('content')
            )
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreMessageRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
