@extends('AllUserLayout.account')
@section('content')
<body bgcolor="#CCCCFF">
    <h3><u>Order Details</u></h3><br><br><br>
    <b>Order ID: </b>{{$val->order_id}} <br><br>
    <b>Vendor ID: </b>{{$val->vendor_name}} <br><br>
    <b>Vendor Name: </b>{{$val->vendor_name}} <br><br>
    <b>Cart ID: </b>{{$val->cart_id}} <br><br>
    <b>Accepted Time: </b>{{$val->accepted_time}} <br><br>
    <b>Delivery TIme: </b>{{$val->delivery_time}} <br><br>
    <b>Contract Status: </b>{{$val->contract_status}} <br><br>
</body>
@endsection