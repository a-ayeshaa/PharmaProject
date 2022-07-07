@extends('AllUserLayout.account')
@section('content')
<body bgcolor="#CCCCFF">
    <table border="1">
        <tr>
            <th>Contract ID</th>
            <th>Vendor ID</th>
            <th>Contract Status</th>
            <th>Medicine Name</th>
        </tr>
        {{-- @foreach ($data as $it)
            <tr>
                <td>{{$it->contract_id->unique()}}</td>
                <td>{{$it->vendor_id}}</td>
                <td>{{$it->contract_status}}</td>
                <td>
                    @foreach ($data as $ig)
                        @if ($ig->contract_id==$it->contract_id)
                            {{$ig->med_name}} <br>
                            
                        @endif
                    @endforeach
                </td>
                <td><a href="{{route('contract.info',['id'=>$it->contract_id])}}">Details</td> 
                @if ($it->contract_status=="Accepted" || $it->contract_status=="Pending")
                    <td><a href="{{route('contract.delete',['id'=>$it->contract_id])}}">Cancel</td>
                @else
                    <td></td>
                @endif
            </tr>
        @endforeach --}}
        @foreach($data as $it)
            <tr>
                <td>{{$it->contract_id}}</td>
                <td>{{$it->vendor_id}}</td>
                <td>{{$it->contract_status}}</td>
                <td>{{$it->med_name}}</td>
                <td><a href="{{route('contract.info',['id'=>$it->contract_id])}}">Details</td> 
                    @if ($it->contract_status=="Accepted" || $it->contract_status=="Pending")
                        <td><a href="{{route('contract.delete',['id'=>$it->contract_id])}}">Cancel</td>
                    @else
                        <td></td>
                    @endif
            </tr>
        @endforeach   
        {{-- @for ($i = 0; $i < count($data)-1; $i++)
            <tr>
                <td>{{$data[$i]->contract_id}}</td>
                <td>{{$data[$i]->vendor_id}}</td>
                <td>{{$data[$i]->contract_status}}</td>
                <td>
                    @for ($j = $i-1; $j > 0; $j--)
                        @if ($data[$i]->contract_id==$data[$j]->contract_id)
                            @break
                        @else
                            {{$data[$i]->med_name}} <br>
                        @endif
                    @endfor
                </td>
                <td><a href="{{route('contract.info',['id'=>$data[$i]->contract_id])}}">Details</td> 
                @if ($data[$i]->contract_status=="Accepted" || $data[$i]->contract_status=="Pending")
                    <td><a href="{{route('contract.delete',['id'=>$data[$i]->contract_id])}}">Cancel</td>
                @else
                    <td></td>
                @endif
            </tr>  
        @endfor --}}
    </table>
    {{$data->links()}}
</body>
@endsection
