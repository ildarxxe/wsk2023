<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    protected function ownsConversation(Conversation $conversation, User $user): bool {
        return $conversation->user_id === $user->id;
    }

    public function continueChat(Conversation $conversation, User $user): bool {
        return $this->ownsConversation($conversation, $user);
    }

    public function getPartialAnswer(Conversation $conversation, User $user): bool {
        return $this->ownsConversation($conversation, $user);
    }
}
