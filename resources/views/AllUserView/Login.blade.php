<h3>{{Session::get('msg')}}</h3>
@extends('AllUserLayout.top')
@section('content')
    <h1>LOG IN</h1>
    <form method="POST" action="">
        {{ csrf_field() }}
        Email: <input type="email" name="email" placeholder="Email" value="">
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
        <input type="submit" name="login" value="LOG IN">
    </form>
@endsection