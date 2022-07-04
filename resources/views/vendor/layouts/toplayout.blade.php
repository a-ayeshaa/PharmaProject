<html>
    <head> 
        <h3>
            
            <button type="button" onclick="window.location='{{route('vendor.home')}}'">Home</button>
        
            <button type="button" onclick="window.location='{{route('vendor.profile')}}'">Profile</button>
            
            <button type="button" onclick="window.location='{{route('vendor.supply')}}'">Supply</button>
        
            <button type="button" onclick="window.location='{{route('vendor.market')}}'">Market</button>
        
            <button type="button" onclick="window.location='{{route('vendor.contracts')}}'">Contracts</button>

        </h3>
    </head>
    <body>
        @yield('content')
    </body>
    <br> <br> <br>
    <a href="{{route('logout')}}">LOGOUT</a>
</html>