<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Requests;


class UserController extends Controller
{

    public function register(Request $request)
    {
        $payload = $request->all();
        if ($errors = $this->validator($payload)->errors()->all()) {
            return $this->fail($errors);
        }

        if($this->create($payload)){
            $token = Auth::guard()->attempt(['email' => $payload['email'], 'password' => $payload['password']]);
            $user = Auth::guard()->user();
            $user['token'] = $token;
            return response()->json($user);
        }
    }

    public function show(Request $request){
        if($user = Auth::guard()->user()){
            return $this->success($user);
        }
        return $this->fail('User must be authenticated');
    }



    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    private function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
