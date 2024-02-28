<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\order_history;
use App\Models\order_item;
use App\Models\payment;
use App\Models\products;
use App\Models\notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class orders_controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    public function index(){
        $orders = order_history::where('user_id', Auth::user()->id)->get();
        return response()->json($orders);
    }

    public function store(Request $request){

        $product = products::find($request->id);

        $product->update([
            'availability' => 'sold',
            'quantity' => '0'
        ]);

        $order = order_item::create([
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $payment = payment::create([
            'user_id' => Auth::user()->id,
            'order_id' => $order->id,
            'payment_method' => 'cash',
            'payment_status' => 'paid'
        ]);

        order_history::create([
            'user_id' => Auth::user()->id,
            'order_id' => $order->id,
            'total_amount' => $product->price,
            'payment_id' => $payment->id,
            'status' => 'paid'
        ]);

        notifications::create([
            'user_id' => $product->user_id,
            'message' => 'Your product has been sold'
        ]);

        notifications::create([
            'user_id' => Auth::user()->id,
            'message' => 'You have successfully placed an order'
        ]);

        return response()->json(['message' => 'Order placed']); 
    }
}
