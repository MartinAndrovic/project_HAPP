<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function index(){

        return view('chat')->with('to_id',null);
    }

    public function openChat(Request $request){
        $to_id = $request->id;
        $messages = auth()->user()->messages()->get()->toArray();
        return view('chat',compact('messages','to_id'));
    }

    public function save_send(Request $request){
        $message = new Message();
        $message->from = auth()->user()->id;
        $message->to = $request->id;
        $message->text = $request->message;
        if($message->save()){
           broadcast(new MessageSent($message));
           return $message->text;
        }
    }
}
