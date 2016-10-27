<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;

class SearchController extends Controller
{
    public function search(Request $request){
        return User::select(['id','name', 'email'])
            ->where('email','like', '%'. $request->value . '%')
            ->orWhere('name', 'like', '%'. $request->value . '%')
            ->get();
    }
}
