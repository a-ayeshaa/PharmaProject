@extends('AllUserLayout.top')
@section('content')
    <h1>REGISTRATION FORM OF {{$type}}</h1>
    <form method="POST" action="">
        {{ csrf_field() }}
        Name : <input type="text" name="name" placeholder="Name" value="{{old('name')}}">
        <br>
        @error('name')
            {{ $message}}<br>
        @enderror
        <br>
        Email : <input type="email" name="email" placeholder="Email" value="{{old('email')}}">
        <br>
        @error('email')
            {{ $message}}<br>
        @enderror
        <br>
        Password : <input type="password" name="password" placeholder="Password" value="">
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
        <br>
        <input type="submit" name="register" value="REGISTER">
    </form>
@endsection