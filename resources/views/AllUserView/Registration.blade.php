@extends('AllUserLayout.top')
@section('content')
    <h1>USER SELECTION</h1>
    <form method="POST" action="">
        {{ csrf_field() }}

        <input type="submit" name="type" value="VENDOR"> <br> <br>
        <input type="submit" name="type" value="MANAGER"> <br> <br>
        <input type="submit" name="type" value="COURIER"> <br> <br>
        <input type="submit" name="type" value="CUSTOMER"> <br> <br>

    </form>
@endsection