@extends('AllUserLayout.account')
@section('content')
<body bgcolor="#CCCCFF">
    <form action="" method="post">
        {{ csrf_field() }}
        <table border="1">
            <tr>
                <th>Vendor ID</th>
                <th>Medicine ID</th>
                <th>Medicine Name</th>
                <th>Stock</th>
                <th>Unit Price</th>
                <th>Quantity</th>
            </tr>
            @foreach ($val as $it)
            <tr>
                <td>{{$it->vendor_id}}</td>
                <td>{{$it->med_id}}</td>
                <td>{{$it->med_name}}</td>
                <td>{{$it->stock}}</td>
                <td>{{$it->price_perUnit}}</td>
                <td><input type="number" name="amount" placeholder="Add quantity" ></td>
                <td><input type="hidden" name="id" value="{{$it->supply_id}}"></td>
                <td><input type="submit" name="add" value="Add to Cart"></td>
            </tr>
            @endforeach

        </table><br><br>
        <input type="submit" name="add" value="View Items">
    </form>
</body>
@endsection
