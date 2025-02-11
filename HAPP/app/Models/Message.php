<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'from',
        'to',
        'text'
    ];

    public function sender(){
        return $this->belongsTo(User::class,'from');
    }

    public function reciever(){
        return $this->belongsTo(User::class,'to');
    }

    public static function saveToDb($text){
        $message=new Message();
        $message->from = 22;
        $message->to = 24;
        $message->text = $text;
        $message->save();
    }
}
