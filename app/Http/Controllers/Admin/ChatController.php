<?php

namespace App\Http\Controllers\Admin;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
        foreach ($chats as $k => $val) {
            $chats[$k]['user_name'] = $val->user_name;
            $chats[$k]['status_view'] = $val->status_view;
            $chats[$k]['agent_name'] = $val->agent_name;
            $chats[$k]['action'] = view('admin.chats.action')->with('user', $val)->with('chat', $val)->render();
            $chats[$k] = $val;
        }

        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords->count(),
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $chats,
        ]);
    }
}
