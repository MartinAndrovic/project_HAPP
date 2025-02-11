@extends('layouts.app')

@section('content')
    <Chat :to="{{$to_id}}" :user_id="{{auth()->user()->id}}" :db_messages="{{json_encode($messages)}}"> </Chat>
@endsection

