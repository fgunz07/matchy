<?php

namespace App\Actions\Conversation;

use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class StoreConversationAction
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
    public function execute(User $user, int $receiver)
    {
        return $user->conversations()->firstOrCreate([
            'receiver_id' => $receiver
        ]);
    }
}
