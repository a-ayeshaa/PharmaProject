@extends('CustomerLayout.top')
@section('content')
    <h3>CART LIST</h3>
    <fieldset style="width:30%">
        <legend> <b>{{Session::get('name')}}</b></legend>
        USER ID: {{Session::get('logged.customer')}} <br>
        CUSTOMER ID : {{Session::get('customer_id')}}
    </fieldset>
    <br><br><br>
    @if ($cart->count()>0)
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
                    <td><a href="{{route('customer.delete.from.cart',['item_id'=>$c->item_id])}}">DELETE</a></td>
            </tr>
            @endforeach
        </table>
        <br><br>
        <b>Subtotal = ${{Session::get('subtotal')}}<b>
        <br><br>

        <form action="" method="POST">
            {{ csrf_field() }}
            <a href=" {{route('customer.show.med')}} ">CONTINUE SHOPPING>></a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            <input type="submit" value="PLACE ORDER">
        </form>

    @else
        <br><br>
        CART IS EMPTY
        <br>
        {{Session::get('msg')}}

    @endif
    <br><br>
    <a href=" {{route('customer.clear.cart')}} ">CLEAR CART</a>
@endsection