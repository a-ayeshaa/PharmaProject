@extends('CustomerLayout.top')
@section('content')
    <h3>CART LIST</h3>
    <fieldset style="width:30%">
        <legend> <b>{{Session::get('name')}}</b></legend>
        USER ID: {{Session::get('logged.customer')}} <br>
        CUSTOMER ID : {{Session::get('customer_id')}}
    </fieldset>
    <br><br><br>
    @if ($orders->count()>0)
        <table border="1">
            <tr>
                <th>OrderID</th>
                <th>ORDER STATUS</th>
                <th>BILL</th>
                <th>ACCEPTED TIME</th>
                <th>DELIVERY TIME</th>
                <th>ORDER ITEMS</th>
            </tr>
            @foreach ($orders as $order)
            <tr>
                <td> {{$order->order_id}} </td>   
                <td> {{$order->order_status}} </td>
                <td> ${{$order->totalbill}} </td>
                <td> {{$order->accepted_time}} </td>
                <td> {{$order->delivery_time}} </td>
                <td> <a href="{{route('customer.order.details',['order_id'=>$order->order_id])}}">VIEW ITEMS ↓</a></td>


            </tr>
            @endforeach
        </table>
        <br><br>

    @else
        <br><br>
        <h3>NO ORDER HAS BEEN PLACED!</h3>
    @endif
@endsection