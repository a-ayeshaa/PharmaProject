<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body bgcolor="#CCCCFF">
    @extends('vendor.layouts.toplayout')
    @section('content')
    
    <center>
        <fieldset style= "width: 560px">
            
            <legend><h3>ACCOUNT INFORMATION OF <label style="color:rgb(44, 41, 221)">{{Str::upper($vendor->vendor_name)}}</label></h3> </legend>
            <h4>
                NAME : {{ $vendor->vendor_name }}
                <br><br><br>
                VENDOR ID : {{ $vendor->vendor_id }}
                <br><br><br>
            
                EMAIL : {{ $vendor->vendor_email }}
            </h4>
            <br>
            <h3><a href=" {{route('vendor.edit.account',['name'=>Session::get('name')])}} ">UPDATE PROFILE INFORMATION</a></h3>
            
    
        </fieldset>
        
        
        

    </center>
    
    
    
    @endsection 

    
</body>
</html>