<?php

namespace App\Models;

use App\Models\Scopes\ConversationScope;
use App\Observers\MessageObserver;
use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable(['conversation_id', 'sender_id', 'content', 'is_read'])]
#[ScopedBy(ConversationScope::class)]
#[ObservedBy(MessageObserver::class)]
class Message extends Model
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory;
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    /**
     * Get all of the users who liked this message.
     */
    public function likers(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likeable', 'likes');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
