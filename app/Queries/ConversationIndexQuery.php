<?php

namespace App\Queries;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

final class ConversationIndexQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Conversation::where('sender_id', $request->user()->id)
            ->orWhere('receiver_id', $request->user()->id)
            ->withCount(['messages']);

        parent::__construct($query, $request);

        $this->allowedFilters()
            ->allowedSorts('created_at', 'messages_count')
            ->allowedIncludes('messages', 'sender', 'receiver', 'lastMessage')
            ->defaultSorts('-created_at');
    }
}
