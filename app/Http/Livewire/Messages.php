<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\{User,Message};

class Messages extends Component
{
    public $sender;
    public $message;
    public $allMessages;
    public $openChat   = false;
    public function render()
    {
        $users = User::all();
        $sender = $this->sender;
        $this->allMessages;
        return view('livewire.messages', compact('users','sender'));
    }
    public function getUser($userId)
    {
        $user =  User::find($userId);
        $this->sender = $user;
        $this->allMessages = Message::where('user_id',auth()->id())->where('receiver_id',$userId)->orWhere('user_id',$userId)->where('receiver_id',auth()->id())->orderBy('id','desc')->get();
        $this->openChat = true;
    }
    public function mountData()
    {
        if (isset($this->sender->id)) {
            $this->allMessages = Message::where('user_id',auth()->id())->where('receiver_id',$this->sender->id)->orWhere('user_id',$this->sender->id)->where('receiver_id',auth()->id())->orderBy('id','desc')->get();
            $not_seen = Message::where('user_id', $this->sender->id)
                                            ->where('receiver_id', auth()->id());
                                    $not_seen->update(['is_seen' => true]);
        }
    }
    public function SendMessage()
    {
        $data = new Message;
        $data->message = $this->message;
        $data->user_id = auth()->id();
        $data->receiver_id = $this->sender->id;
        $data->save();
        $this->resetForm();
    }
    public function resetForm()
    {
        $this->message='';
    }
    public function closeChat()
    {
        return $this->openChat = false;
    }
}
