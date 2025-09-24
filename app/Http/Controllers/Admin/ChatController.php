<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.chats.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $chat = Chat::with("user", "agent", "messages")->findOrFail($id);
        $messages = $chat->messages()->orderBy("id", "DESC")->get();
        return view("admin.chats.edit", compact("chat", "messages"));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "status" => "required"
        ]);
        $chat = Chat::findOrFail($request->chat_id);
        $chat->update([
            "status" => $request->status
        ]);
        return back()->with("success", "Chat updated Successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function dataTable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];
        $iTotalRecords = new Chat;
        $chats = new Chat;

        if (!empty($search)) {
            $chats = $chats->search($search);
        }
        $totalRecordswithFilter = clone $chats;
        /*Set limit offset */
        $chats = $chats->offset(intval($data['start']));
        $chats = $chats->limit(intval($data['length']));

        $chats = $chats->latest()->get();
        $chats->append(["user_name", "agent_name", "status_view", "action"]);

        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords->count(),
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $chats,
        ]);
    }

    public function viewMessages(Request $request)
    {
        $chat = Chat::with("messages.sender")->findOrFail($request->id);
        $messages = $chat->messages()->with("sender")->get();
        $view = view("admin.chats.ajax.view_messages", compact("messages", "chat"));
        $response = [
            "success" => true,
            "data" => $view->render()
        ];
        return response()->json($response);
    }

    public function newMessages(Request $request)
    {
        $chat = Chat::with("messages")->findOrFail($request->id);
        try {
            DB::beginTransaction();
            $chat->messages()->create([
                "sender_type" => "agent",
                "sender_id" => Auth::id(),
                "content" => $request->message
            ]);
            if ($chat->status == "pending_agent") {
                $chat->status = "agent_assigned";
                $chat->agent_id = Auth::id();
                $chat->save();
            }
            $response = [
                "success" => true,
                "message" => "New message sent Successfully!",
            ];
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $response = [
                "success" => false,
                "message" => $e->getMessage(),
            ];
        }
        return response()->json($response);
    }
    public function pendingCount()
    {
        $chat_counts = pendingChatsCount();
        return response()->json($chat_counts);
    }
}
