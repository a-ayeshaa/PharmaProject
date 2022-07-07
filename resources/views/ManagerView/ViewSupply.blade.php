@extends('AllUserLayout.account')
@section('content')
<body bgcolor="#CCCCFF">
    <table border="1">
        <tr>
            <th>Vendor ID</th>
            <th>Medicine ID</th>
            <th>Medicine Name</th>
            <th>Stock</th>
            <th>Unit Price</th>
        </tr>
        @foreach ($data as $it)
        <tr>
            <td>{{$it->vendor_id}}</td>
            <td>{{$it->med_id}}</td>
            <td>{{$it->med_name}}</td>
            <td>{{$it->stock}}</td>
            <td>{{$it->price_perUnit}}</td>
            <td><a href="{{route('supply.info',['id'=>$it->supply_id])}}">Details</td>
        </tr>
        @endforeach

    </table>
    {{$data->links()}}
</body>
@endsection
