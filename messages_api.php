<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\messages;
use Illuminate\Support\Facades\Auth;

class messages_api extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        $messages = messages::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function destroy($id){
        $message_id = messages::where('id', $id)->first();
        if (!$message_id) {
            return response()->json(['message' => 'Message not found'], 404);
        } else {
            $message_id->delete();
            return response()->json(['message' => 'Message deleted']);
        }
    }
    

    public function store(Request $request){
        $request->validate([
            'receiver_id' => 'required|exists:user_table,id',
            'text_message' => 'required',
            'message_type' => 'required'
        ]);
    
        $message = messages::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'user_id' => Auth::id(), // Added 'user_id' to the fillable array in the messages model
            'message' => $request->text_message, // Updated to use 'text_message'
            'message_type' => $request->message_type
        ]);
    
        return response()->json(['message' => $message], 201);
    }
    
}
