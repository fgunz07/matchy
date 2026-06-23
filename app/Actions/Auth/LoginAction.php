<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\QueueableAction\QueueableAction;

class LoginAction
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
    public function execute(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if(!$user || !Hash::check($password, $user->password)) {
            abort(401, 'Invalid email or password');
        }

        $expiration = now()->addHours(24);

        return [
            "user" => $user->toArray(),
            "token" => $user->createToken(
                name: "authToken",
                abilities: [],
                expiresAt: $expiration
            )->plainTextToken,
            "expires_at" => $expiration,
        ];
    }
}
