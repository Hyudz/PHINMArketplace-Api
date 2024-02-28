<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cart_items;
use Illuminate\Support\Facades\Auth;

class cart_api extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');    
    }

    public function index(){
        try {
            $likes = cart_items::where('user_id', Auth::user()->id)->get();
            return $likes;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function store(Request $request){
        cart_items::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->id
        ]);
        return response()->json(['message' => 'Product added to cart']);
    }

    public function destroy(Request $request){
        $user = auth()->user(); // Retrieve the authenticated user object
        $user = auth('sanctum')->user();
    
        if ($user) {
            $userId = $user->id; // Get the user ID from the user object
    
            $like = cart_items::where('user_id', $userId)
                                ->where('product_id', $request->id)
                                ->first();
    
            if ($like) {
                $like->delete();
                return response()->json(['message' => 'Product removed to cart']);
            } else {
                return response()->json(['message' => 'Product not found to cart'], 404);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
