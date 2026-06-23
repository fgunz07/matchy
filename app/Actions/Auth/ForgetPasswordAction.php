<?php

namespace App\Actions\Auth;

use Spatie\QueueableAction\QueueableAction;
use Illuminate\Support\Facades\Password;

class ForgetPasswordAction
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
    public function execute(string $email)
    {
        $status = Password::broker()->sendResetLink(
            ['email' => $email]
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => [__($status)]], 422);
    }
}
