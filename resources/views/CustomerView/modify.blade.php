@extends('CustomerLayout.top')
@section('content')
    <h3>MODIFY ACCOUNT INFORMATION OF {{Str::upper($customer->customer_name)}}</h3> 

    <form action="" method="POST" >
        {{ csrf_field() }}
        USER ID : <input type="text" name="u_id" placeholder=" {{$customer->users->u_id}}" value=" {{$customer->users->u_id}} " readonly>
        <br><br>
        CUSTOMER ID : <input type="text" name="customer_id" placeholder=" {{$customer->customer_id}}" value=" {{$customer->customer_id}} " readonly>
        <br><br>
        Name: <input type="text" name="name" placeholder="{{$customer->customer_name}}" value=" {{$customer->customer_name}} ">
        <br>
        @error('name')
            {{ $message}}<br>
        @enderror
        <br>
        Email: <input type="email" name="email" placeholder="{{$customer->customer_email}}" value=" {{$customer->customer_email}} ">
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
        <input type="submit" name="modify" value="MODIFY">
    </form>
@endsection