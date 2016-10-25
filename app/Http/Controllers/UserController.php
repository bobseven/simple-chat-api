<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Requests;

use JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;


class UserController extends Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    }

    public function register(Request $request)
    {
        $payload = $request->json()->all();
        //TODO: Will eventually integrate with another micro service to create an account send post.
        if ($errors = $this->validator($payload)->errors()->all()) {
            return $this->fail($errors);
        }

        return $this->create($payload);
    }

    public function show(Request $request){
        if(Auth::user()){
            return $this->success(Auth::user());
        }
        return $this->fail('User must be authenticated');
    }

    public function login(Request $request){
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];


        if( Auth::attempt($credentials) ) {
            return $this->success(Auth::user());
        }
        return $this->fail('Unable to login user, please try again.');
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
