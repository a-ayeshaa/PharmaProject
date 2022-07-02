@extends('CustomerLayout.top')
@section('content')
    <h3>MEDICINE LIST</h3>
    <h4>
        <table border="1">
            <tr>
                <th>Medicine Name</th>
                <th>Price per Unit</th>
                <th>Quantity</th>
            </tr>

            @foreach ($meds as $med)
                <tr>
                    <td>{{ $med->med_name }}</td>
                    <td>{{ $med->price_perUnit }}</td>
                    <form action="" method="POST">
                    {{csrf_field()}}
                    <td><input type="number" name="quantity" placeholder="Type quantity here" value=""></td>
                    <input type="hidden" name="med_id" value=" {{$med->med_id}} ">
                    <td><input type="submit" name="add" placeholder="" value="ADD TO CART"></td>
                    </form>
                </tr>   
            @endforeach
        </table>
        @error('quantity')
            {{$message}}
        @enderror
    </h4>
@endsection
