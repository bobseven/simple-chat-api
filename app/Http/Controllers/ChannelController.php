<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
{
    /**
     * Http Client for contacting stream API
     */
    private $client;

    /**
     * Init the Guzzle Http client.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('CHAT_STREAM_URL', 'http://192.168.0.6:5000/')
        ]);
    }

    /**
     * Send a message to a specific channel
     * @param Request $request
     * @return array - contents of newly created message
     */
    public function send(Request $request)
    {
        $payload = $request->all();

        //TODO: This will eventually send a message to a separate micro-service for processing
        if ($errors = $this->validator($payload)->errors()->all()) {
            return $this->fail($errors);
        }

        return $this->success($this->newMessage($payload));
    }

    /**
     * Show all messages from a specific channel
     * @param Request $request
     * @return array
     */
    public function show(Request $request)
    {
        return $this->success(Message::where('channel_id', $request->id)->get());
    }

    /**
     * Create a new channel between authenticated user and specified user's id.
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        return $this->newChannel($request->userId);
    }

    /**
     * Show all channels that a user is part of.
     * @param Request $request
     * @return mixed
     */
    public function showAll(Request $request)
    {
        return $this->listChannels();
    }

    /**
     * Validate input for making a new message
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'channel_id' => 'required|max:255',
            'message' => 'required|max:255',
        ]);
    }

    /**
     * Inserts new message into database and returns its contents.
     * @param array $data
     * @return static
     */
    private function newMessage(array $data)
    {

        $message = Message::create([
            'channel_id' => $data['channel_id'],
            'user_id' => Auth::guard()->user()->id,
            'message' => $data['message']
        ]);
        if ($message) {
            $this->notifyChannelUsers($data['channel_id']);
        }
        return $message;
    }

    /**
     * Inserts a new db record for a channel.
     * @param $userId
     * @return mixed
     */
    private function newChannel($userId)
    {
        $channel = Channel::create(['description' => 'test']);
        $recipient = User::find($userId);
        Auth::guard()->user()->channels()->attach($channel->id);
        $recipient->channels()->attach($channel->id);
        return $this->listChannels();
    }

    /**
     * Returns a list of channels for currently authenticated user from database.
     * @return mixed
     */
    private function listChannels()
    {
        return Auth::guard()->user()->channels->load('users');
    }

    /**
     * Notifies all users on channel of a new message
     *
     * @param $channelId
     */
    private function notifyChannelUsers($channelId)
    {
        $this->notifyBySocket($channelId);
        $this->notifyByEmail($channelId);
    }

    /**
     * Send request to stream API to update websocket clients
     * @param $channelId
     */
    private function notifyBySocket($channelId)
    {
        $this->client->request('POST', 'flare/' . $channelId);
    }

    /**
     * Sends email message to clients who are offline.
     * @param $channelId
     */
    private function notifyByEmail($channelId)
    {
        //TODO: Sends email notification is users is not currently online / connected to websocket.
    }
}
