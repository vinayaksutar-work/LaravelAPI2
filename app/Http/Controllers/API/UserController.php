<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'phone' => 'required|numeric',
            'password' => 'required|min:6'
        ]);
        if($validator->fails())
        {
            $result = array(['status'=>false,'message'=>'Validation error occured',
            'error_message'=>$validator->errors()]);
            return response()->json($result,400); //bad request
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>bcrypt($request->password)
        ]);

        if($user->id)
        {
            $result = array(['status'=>true,'message'=>'User created successfully','data'=>$user]);
            $responseCode=200; //success
        }
        else{
            $result = array(['status'=>false,'message'=>'Something went wrong','data'=>$user]);
            $responseCode=400; //bad request
        }
        return response()->json($result,$responseCode);
    }
    public function getUsers()
    {
        $users = User::all();
        $result = array(['status'=>true,'message'=> count($users).' users fetched','data'=>$users]);
        $responseCode=200; //success
        return response()->json($result,$responseCode);
    }
}
