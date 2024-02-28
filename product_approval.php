<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\products;

class product_approval extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(){
        $products = products::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $products
        ]);
    }

    public function approve(Request $request){
        $product = products::find($request->id);
        $product->update([
            'availability' => 'approved'
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Product approved successfully',
            'data' => $product
        ]);
    }

    public function decline(Request $request){
        $product = products::find($request->id);
        $product->update([
            'availability' => 'declined'
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Product declined successfully',
            'data' => $product
        ]);
    }
}
