@extends('CustomerLayout.top')
@section('content')
    <h3>CART LIST</h3>
    <fieldset style="width:30%">
        <legend> <b>{{Session::get('name')}}</b></legend>
        USER ID: {{Session::get('logged.customer')}} <br>
        CUSTOMER ID : {{Session::get('customer_id')}}
    </fieldset>
    <br><br><br>
    <table border="1">
        <tr>
            <th>Medicine Name</th>
            <th>Price per Unit</th>
            <th>Purchased Quantity</th>
            <th>Total</th>
        </tr>
        @foreach ($cart as $c)
        <tr>
                <td>{{$c->med_name}}</td>
                <td>{{'$'.$c->price_perUnit}}</td>
                <td>{{$c->quantity}}</td>
                <td>{{'$'.$c->total}}</td>
        </tr>
        @endforeach
    </table>

    <br><br>
    Subtotal = {{Session::get('subtotal')}}
@endsection