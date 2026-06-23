<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the messages table.
     */
    public function run(): void
    {
        $conversations = Conversation::all();

        // Create messages for each conversation
        foreach ($conversations as $conversation) {
            // Create 5-15 messages per conversation
            Message::factory()
                ->count(rand(5, 15))
                ->forConversation($conversation)
                ->create();

            // Update last_message_at
            $lastMessage = $conversation->messages()->latest()->first();
            if ($lastMessage) {
                $conversation->update(['last_message_at' => $lastMessage->created_at]);
            }
        }
    }
}
