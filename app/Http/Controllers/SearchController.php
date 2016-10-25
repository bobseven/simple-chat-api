<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Requests;
use Validator;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    }

    public function search(Request $request){
        return User::select(['id','name', 'email'])
            ->where('email','like', '%'. $request->value . '%')
            ->orWhere('name', 'like', '%'. $request->value . '%')
            ->get();
    }
}
