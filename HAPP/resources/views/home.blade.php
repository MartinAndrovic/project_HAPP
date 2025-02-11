@extends('layouts.app')

@section('navbar_right')
    @parent

    @section('components')
        <Notifications> </Notifications>
    @endsection


@endsection

@section('content')

        <Home type="{{$type}}"> </Home>

@endsection




