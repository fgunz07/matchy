<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the likes table.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            return;
        }

        // Create likes between random users
        $users->each(function ($user) use ($users) {
            // Each user likes 3-8 other random users
            $likeCount = rand(3, min(8, $users->count() - 1));

            $usersToLike = $users
                ->where('id', '!=', $user->id)
                ->random($likeCount);

            foreach ($usersToLike as $likedUser) {
                // Prevent duplicate likes
                $exists = Like::where('user_id', $user->id)
                    ->where('likeable_type', 'App\\Models\\User')
                    ->where('likeable_id', $likedUser->id)
                    ->exists();

                if (!$exists) {
                    Like::factory()
                        ->state([
                            'user_id' => $user->id,
                            'likeable_type' => 'App\\Models\\User',
                            'likeable_id' => $likedUser->id,
                        ])
                        ->create();
                }
            }
        });
    }
}
