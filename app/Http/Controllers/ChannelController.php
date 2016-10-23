<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Message;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
{

    public function send(Request $request)
    {
        $payload = $request->all();

        //TODO: This will eventually send a message to a separate micro-service for processing
        if ($errors = $this->validator($payload)->errors()->all()) {
            return $this->fail($errors);
        }

        return $this->success($this->newMessage($payload));
    }

    public function show(Request $request)
    {
        return Message::where('channel_id', $request->id)->get();
    }


    public function create(Request $request)
    {
        return $this->newChannel($request->userId);
    }

    public function showAll(Request $request)
    {
        return $this->listChannels();
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'channel_id' => 'required|max:255',
            'message' => 'required|max:255'
        ]);
    }

    private function newMessage(array $data)
    {
        return Message::create([
            'channel_id' => $data['channel_id'],
            'user_id' => Auth::user()->id,
            'message' => $data['message']
        ]);
    }

    private function newChannel($userId)
    {
        $channel = Channel::create(['description' => 'test']);
        $recipient = User::find($userId);
        if ($user = Auth::user()) {
            $user->channels()->attach($channel->id);
            $recipient->channels()->attach($channel->id);
            return $this->listChannels();
        }
        return $channel;
    }

    private function listChannels()
    {
        return Auth::user()->channels;
    }
}
