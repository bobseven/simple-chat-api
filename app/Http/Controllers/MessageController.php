<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Message;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function send(Request $request)
    {
        //TODO: This will eventually send a message to a separate micro-service for processing
        if ($errors = $this->validator($request->all())->errors()->all()) {
            return $this->fail($errors);
        }

        return $this->success($this->create($request->all()));
    }

    public function getAllMessages(Request $request)
    {
        return Message::where('channel_id', $request->id)->get();
    }


    private function validator(array $data)
    {
        return Validator::make($data, [
            'channel' => 'max:255',
            'message' => 'required|max:255'
        ]);
    }

    private function create(array $data)
    {
        return Message::create([
            'channel_id' => $data['channel'] ? $data['channel'] : $this->newChannel(),
            'user_id' => Auth::user()->id,
            'message' => $data['message']
        ]);
    }

    private function newChannel()
    {
        return Channel::create()->id;
    }
}
