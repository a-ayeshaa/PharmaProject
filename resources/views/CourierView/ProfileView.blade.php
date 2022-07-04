@extends('CourierView.layouts.app')
@section('content')
<center>
<fieldset style="width:30%">
    <legend><h3>{{$courier->courier_name}}</h3></legend> 

    <h4>
        {{-- <img src="{{ asset("storage/profilepictures/".Session::get('logged.courier').".jpg")}}" alt="" srcset="" height="150" width="120" rounded-full> --}}
        
        <br>
        NAME : {{ $courier->courier_name }}
        <br>
        courier ID : {{ $courier->courier_id }}
        <br>
        USER ID: {{$courier->users->u_id}}
        <br>
        EMAIL : {{ $courier->courier_email }}
    </h4>
    <br>
</fieldset>
</center>
@endsection