<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Services\ChatbotService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        // If no authenticated user and no guest_identifier, return error
        if (!$user) {
            return response()->json(['message' => 'Authentication is required.'], 401);
        }

        // Handle the user message and get chatbot responses
        $chatbotResponses = $this->chatbotService->handleUserMessage(
            $request->message
        );

        // Get the current chat session
        $chat = $this->findChat($user);

        // Fetch all messages for the current chat to send back to the mobile app
        $messages = Message::where('chat_id', $chat->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $response =  [
            'status' => 'success',
            'messages' => $messages,
            'chat_status' => $chat->status,
            'is_automated' => $chat->is_automated,
        ];
        return $this->successResponse($response);
    }

    /**
     * Retrieves all messages for a given chat session.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChatHistory(Request $request)
    {
        $user = Auth::user();
        if (!$user && !$request->filled('guest_identifier')) {
            return response()->json(['message' => 'Authentication or guest identifier is required.'], 401);
        }
        $chat = $this->findChat($user);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found.'], 404);
        }
        $messages = Message::where('chat_id', $chat->id)
            ->orderBy('created_at', 'asc')
            ->get();
        $response = [
            'status' => 'success',
            'messages' => $messages,
            'chat_status' => $chat->status,
            'is_automated' => $chat->is_automated,
        ];
        return $this->successResponse($response);
    }


    /**
     * Helper to find the current chat session.
     *
     * @param \App\Models\User|null $user
     * @param string|null $guestIdentifier
     * @return Chat|null
     */
    protected function findChat(?\App\Models\User $user): ?Chat
    {
        if ($user) {
            return Chat::where('user_id', $user->id)->whereIn('status', ['open', 'pending_agent', 'agent_assigned'])->first();
        }
        return null;
    }
}
