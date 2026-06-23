<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'likeable_type' => 'App\\Models\\User',
            'likeable_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the user likes another user.
     */
    public function likeUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'likeable_type' => 'App\\Models\\User',
            'likeable_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the user likes a message.
     */
    public function likeMessage(int $messageId): static
    {
        return $this->state(fn (array $attributes) => [
            'likeable_type' => 'App\\Models\\Message',
            'likeable_id' => $messageId,
        ]);
    }
}
