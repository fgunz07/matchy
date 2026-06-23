<?php

namespace App\Actions\Auth;

use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

final class GoogleCallbackAction
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
    public function execute(mixed $googleUser)
    {
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'avatar_url' => $googleUser->getAvatar(),
                'name' => $googleUser->getName(), 'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'password' => now(),
            ]
        );

        $expiration = now()->addHours(24);

        $token = $user->createToken(
            name: 'authToken',
            abilities: [],
            expiresAt: $expiration
        )->plainTextToken;

        return config('frontend.url')."/callback?token={$token}";
    }
}
