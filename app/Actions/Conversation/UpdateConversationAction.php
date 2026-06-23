<?php

namespace App\Actions\Conversation;

use App\Models\Conversation;
use Spatie\QueueableAction\QueueableAction;

class UpdateConversationAction
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
    public function execute(Conversation $conversation, array $data)
    {
        return $conversation->update($data);
    }
}
