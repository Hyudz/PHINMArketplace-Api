<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\user_table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Admin_api_controller extends Controller
{

    public function signin(Request $request){
    
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
            $user = user_table::where('email', $request->email)->firstOrFail();

            if ($user->user_type !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $token = $user->createToken('admin_token')->plainTextToken;
            return response()->json(['token' => $token]);
    
    }
    
}
