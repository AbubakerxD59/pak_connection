<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChatbotService
{
    /**
     * Handle an incoming user message and generate a chatbot response.
     *
     * @param string $userMessage
     * @param string|null $guestIdentifier A unique ID for unauthenticated users.
     * @return Message|array The chatbot's response message(s) or an array of messages.
     */
    public function handleUserMessage(string $userMessage, ?string $guestIdentifier = null)
    {
        $user = Auth::user();
        $chat = $this->findOrCreateChat($user, $guestIdentifier);

        // Save the user's message
        Message::create([
            'chat_id' => $chat->id,
            'sender_type' => 'user',
            'sender_id' => $user ? $user->id : null,
            'content' => $userMessage,
        ]);

        // If an agent is assigned, the chatbot should not respond
        if ($chat->status === 'agent_assigned') {
            return null; // Agent is handling, no chatbot response
        }

        // --- Chatbot Logic ---
        $responseMessages = [];

        if ($chat->is_first_contact) {
            $responseMessages[] = $this->sendWelcomeMessage($chat);
            $chat->is_first_contact = false;
            $chat->save();
        }

        // If automated, generate a response
        if ($chat->is_automated) {
            $responseMessages[] = $this->generateAutomatedResponse($userMessage, $chat);
        }

        return $responseMessages; // Return all generated chatbot responses
    }

    /**
     * Finds an existing chat or creates a new one for the user/guest.
     *
     * @param \App\Models\User|null $user
     * @param string|null $guestIdentifier
     * @return Chat
     */
    protected function findOrCreateChat(?\App\Models\User $user, ?string $guestIdentifier): Chat
    {
        $chat = null;

        if ($user) {
            $chat = Chat::where('user_id', $user->id)->whereIn('status', ['open', 'pending_agent', 'agent_assigned'])->first();
        } elseif ($guestIdentifier) {
            $chat = Chat::where('guest_identifier', $guestIdentifier)->whereIn('status', ['open', 'pending_agent'])->first();
        }

        if (!$chat) {
            $chat = Chat::create([
                'user_id' => $user ? $user->id : null,
                'guest_identifier' => $guestIdentifier ?? (Str::uuid())->toString(), // Generate UUID for new guests
                'is_first_contact' => true,
                'is_automated' => true,
                'status' => 'open',
            ]);
        }

        return $chat;
    }

    /**
     * Sends the pre-scripted welcome message.
     *
     * @param Chat $chat
     * @return Message
     */
    protected function sendWelcomeMessage(Chat $chat): Message
    {
        $welcomeMessageContent = "Hello! Welcome to our support chat. I'm a chatbot here to help you get started. How can I assist you today?";
        return Message::create([
            'chat_id' => $chat->id,
            'sender_type' => 'chatbot',
            'content' => $welcomeMessageContent,
        ]);
    }

    /**
     * Generates an automated response based on the user's message.
     *
     * @param string $userMessage
     * @param Chat $chat
     * @return Message
     */
    protected function generateAutomatedResponse(string $userMessage, Chat $chat): Message
    {
        $responseContent = "I'm still learning! Could you please rephrase that, or tell me if you'd like to speak to a human agent? Type 'agent' to connect.";

        // Simple keyword matching for demonstration
        $userMessageLower = strtolower($userMessage);

        if (Str::contains($userMessageLower, ['hello', 'hi', 'hey'])) {
            $responseContent = "How can I help you today? You can ask about our services, pricing, or type 'agent' if you need human assistance.";
        } elseif (Str::contains($userMessageLower, ['pricing', 'cost', 'how much'])) {
            $responseContent = "You can find all our pricing details on our website's pricing page. Is there a specific product or service you're interested in?";
        } elseif (Str::contains($userMessageLower, ['agent', 'human', 'talk to someone'])) {
            $responseContent = "No problem! I'm connecting you to a human agent now. Please wait a moment. They will be with you shortly.";
            $chat->is_automated = false;
            $chat->status = 'pending_agent';
            $chat->save();
        }

        return Message::create([
            'chat_id' => $chat->id,
            'sender_type' => 'chatbot',
            'content' => $responseContent,
        ]);
    }
}
