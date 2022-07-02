@extends('AllUserLayout.account')
@section('content')
<body bgcolor="#CCCCFF">
    <table border="1">
        <tr>
            <th>Medicine Id</th>
            <th>Name</th>
            <th>Manufacturing Date</th>
            <th>Expiry Date</th>
            <th>Unit Pirice</th>
        </tr>
        @foreach ($data as $it)
        <tr>
            <td>{{$it->med_id}}</td>
            <td>{{$it->med_name}}</td>
            <td>{{$it->manufacturingDate}}</td>
            <td>{{$it->expiryDate}}</td>
            <td>{{$it->price_perUnit}}</td>
            <td><a href="{{route('med.info',['id'=>$it->med_id])}}">Details</td>
            <td><a href="{{route('med.delete',['id'=>$it->med_id])}}">Delete</td>
        </tr>
        @endforeach

    </table>
</body>
@endsection
