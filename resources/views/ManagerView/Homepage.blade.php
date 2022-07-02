{{-- {{ url()->previous() }} --}}
@extends('AllUserLayout.account')
@section('content')
<h2>Hello, {{Session::get('name')}}</h2>
<center>
    <body bgcolor="#CCCCFF">
    <fieldset style= "width: 360px">
        <center>
            <legend><h2><b><u>Available Actions</u></b></h2></legend><br><br>
        </center>
        <form action="" method="post">
            {{ csrf_field() }}
            <input type='submit' name='action' value='View Users'> &nbsp; &nbsp;
            <input type='submit' name='action' value='View Medicine'> &nbsp; &nbsp;
            <input type='submit' name='action' value='View Orders'> &nbsp; &nbsp;<br><br><br><br><br>
            <input type='submit' name='action' value='View Contracts'> &nbsp; &nbsp;
            <input type='submit' name='action' value='View Supply'> &nbsp; &nbsp;
            <input type='submit' name='action' value='Go to Cart'> &nbsp; &nbsp;<br><br>
        </form>
    </fieldset>
    </body>
    </center>
@endsection


