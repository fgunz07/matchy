<?php

namespace App\Actions\User;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Support\Arr;
use Spatie\QueueableAction\QueueableAction;

class UpdateUserAction
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
    public function execute(User $user, UserData $data)
    {
        $payload = $data->toArray();
        Arr::forget($payload, 'password');
        return tap($user)->update(
            $payload
        );
    }
}
