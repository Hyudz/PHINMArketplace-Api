<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\likes_table;
use App\Models\products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class likes_api extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');    
    }

    public function index(){
        $likes = likes_table::where('user_id', Auth::user()->id)->get();
        $products = products::whereIn('id', $likes->pluck('product_id'))
        ->where('availability', '=', 'approved')
        ->get();
        return response()->json($products);
    }

    public function store(Request $request){
        likes_table::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->id
        ]);
        return response()->json(['message' => 'Product liked']);
    }

    public function destroy(Request $request){
        $user = auth()->user();
        $user = auth('sanctum')->user();
    
        if ($user) {
            $userId = $user->id;
    
            $like = likes_table::where('user_id', $userId)
                                ->where('product_id', $request->id)
                                ->first();
    
            if ($like) {
                $like->delete();
                return response()->json(['message' => 'Product unliked']);
            } else {
                return response()->json([$user], 404);
            }
        } else {
            Log::error("Authenticated user not found");
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
    
}
