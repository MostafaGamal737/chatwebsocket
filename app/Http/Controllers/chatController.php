<?php

namespace App\Http\Controllers;
use App\message;
use App\user;
use Auth;
use App\Events\sendMessageEvent;
use App\Events\onlineEvent;

use Illuminate\Http\Request;
class chatController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      broadcast(new onlineEvent(Auth::user()))->toOthers();
      return view ('chat');
    }
    public function sendmessages(Request $M)
    {
      $message=new message();
      $message->massege=$M->messege;
      $message->user_id=Auth::id();
      $message->save();
      broadcast(new sendMessageEvent($message->load('user')))->toOthers();
      return ['stauts'=>"massage added successfull"];
    }
    public function getmessages()
    {

      //return Auth::user()->with('messages')->get();
     return message::with('user')->get();
    }
}
