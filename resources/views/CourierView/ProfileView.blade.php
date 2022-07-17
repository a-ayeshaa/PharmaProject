@extends('CourierView.layouts.app')
@section('content')
    <h3>Profile {{Str::upper($courier->courier_name)}}</h3> 

    <h4>Delivery Amount {{$courier->due_delivery_fee}}</h4>
    <a href="{{route('courier.cashoutView',['id',$courier->u_id])}}">Cashout</a>

    <form action="" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        User Id : <input type="text" name="u_id" placeholder=" {{$courier->u_id}}" value=" {{$courier->u_id}} " readonly>
        <br><br>
        Courier Id : <input type="text" name="courier_id" placeholder=" {{$courier->courier_id}}" value=" {{$courier->courier_id}} " readonly>
        <br><br>
        Name: <input type="text" name="name" placeholder="{{$courier->courier_name}}" value=" {{$courier->courier_name}} ">
        <br>
        @error('name')
            {{ $message}}<br>
        @enderror
        <br>
        Email: <input type="email" name="email" placeholder="{{$courier->courier_email}}" value=" {{$courier->courier_email}} " readonly>
        <br>
        @error('email')
            {{ $message}}<br>
        @enderror
        <br>
        Password: <input type="password" name="password" placeholder="Password" value="">
        <br>
        @error('password')
            {{ $message}}<br>
        @enderror
        <br>
        Confirm Password : <input type="password" name="confirmPassword" placeholder="Re-enter Password" value="">
        <br>
        @error('confirmPassword')
            {{ $message}}<br>
        @enderror
        <input type="submit" name="modify" value="Change">

        
    </form>
@endsection