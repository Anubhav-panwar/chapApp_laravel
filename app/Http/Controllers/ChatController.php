<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Chatting;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function Allchat()
    {
        $Person = User::where('id', '!=', auth()->id())->get();;
        return view('chat.chat',compact('Person'));
    }



    public function Showdetails(Request $request){
        $id=$request->showid;
        $Users = User::find($id); //reciever
        $SenderID = Auth()->id();
        $Sendermessages = Chatting::where('sender_id',$SenderID)
        ->where('user_id',$Users->id)
        ->orderBy('created_at', 'asc')
        ->get();

        $Recievermessages = Chatting::where('sender_id',$Users->id)
        ->where('user_id',$SenderID)
        ->orderBy('created_at', 'asc')
        ->get();
       
        return response()->json([
            'Users' => $Users,
            'Messages' => $Sendermessages,
            'Receiver'=>$Recievermessages
        ]);
    }

    public function SaveChat(Request $request)
    {
        $data = new Chatting;
        $data->message = $request->input('message');
        $data->user_id = $request->input('reciver_id');
        $data->sender_id = $request->input('sender_id');
        $data->save();

        return response()->json(['success' => true]);
    }
}
