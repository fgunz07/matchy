<?php

namespace App\Actions\Auth;

use App\Data\UserData;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class RegisterAction
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
    public function execute(UserData $data)
    {
       $user = User::create($data->toArray());

       $user->sendEmailVerificationNotification();

       return $user;
    }
}
