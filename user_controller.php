<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user_table;
use Illuminate\Support\Facades\Hash;

class user_controller extends Controller
{
    public function index(){
        return user_table::with('products')->get();
    }

    public function store(Request $request){
        $data['first_name'] = $request->input('first_name');
        $data['last_name'] = $request->input('last_name');
        $data['email'] = $request->input('email');
        $data['password'] = Hash::make($request->input('password'));
        $data['user_type'] = $request->input('user_type');
        $data['gender'] = $request->input('gender');
        $data['birthday'] = $request->input('birthday');
        $user = user_table::create($data);
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not created'
            ]);
        }
    }

    public function update(Request $request, $id){
        $user = user_table::findOrFail($id);

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'user_type' => 'required',
            'gender' => 'required',
            'birthday' => 'required'
        ]);


        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Added password update
            'user_type' => $request->user_type,
            'gender' => $request->gender,
            'birthday' => $request->birthday

        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => $user
        ]);

    }

    public function destroy($id){
        $user = user_table::findOrFail($id);
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
            'data' => $user
        ]);
    }

    public function getData(Request $request)
    {
        // Ensure the request is authenticated (using Sanctum)
        $user = $request->user();
        
        // Your logic to fetch data goes here
        $data = ['message' => 'Data fetched successfully', 'user' => $user];

        return response()->json($data);
    }
}
