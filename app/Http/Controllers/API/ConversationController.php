<?php

namespace App\Http\Controllers\API;

use App\Actions\Conversation\StoreConversationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Conversation\StoreConversationRequest;
use App\Http\Resources\Conversation\ConversationCollection;
use App\Http\Resources\Conversation\ConversationResource;
use App\Http\Responses\ResponsePaginated;
use App\Models\Conversation;
use App\Models\User;
use App\Queries\ConversationIndexQuery;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware(HandlePrecognitiveRequests::class, ['store'])]
class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user, ConversationIndexQuery $query)
     {
        return new ResponsePaginated(
            new ConversationCollection(
                $query->paginate(
                    perPage: request()->input('per_page', 15)
                )
            )
        );
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversationRequest $request, StoreConversationAction $action)
    {
        return new ConversationResource(
            $action->execute(
                $request->user(),
                $request->input('receiver_id')
            )
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        return new ConversationResource(
            $conversation->load(['sender', 'receiver'])
                ->loadCount('messages')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        return new ConversationResource(
            tap($conversation)->delete()
        );
    }
}
