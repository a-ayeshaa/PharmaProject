@extends('AllUserLayout.account')
@section('content')
<body bgcolor="#CCCCFF">
    <table border="1">
        <tr>
            <th>Contract ID</th>
            <th>Vendor ID</th>
            <th>Contract Status</th>
        </tr>
        @foreach ($data as $it)
        <tr>
            <td>{{$it->contract_id}}</td>
            <td>{{$it->vendor_id}}</td>
            <td>{{$it->contract_status}}</td>
            <td><a href="{{route('contract.info',['id'=>$it->contract_id])}}">Details</td>
            @if ($it->contract_status=="Accepted")
            {
                <td><a href="{{route('contract.delete',['id'=>$it->contract_id])}}">Delete</td>
            }   
            @else
            {
                <td></td>
            }
            @endif
        </tr>
        @endforeach

    </table>
</body>
@endsection
