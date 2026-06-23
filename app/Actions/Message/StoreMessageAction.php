<?php

namespace App\Actions\Message;

use App\Models\Conversation;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class StoreMessageAction
{
    use QueueableAction;

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Prepare the action for execution, leveraging constructor injection.
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute(User $user, Conversation $conversation, string $content)
    {
        return $conversation->messages()->create([
            'sender_id' => $user->id,
            'content' => $content,
        ]);
    }
}
