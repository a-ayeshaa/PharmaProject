<html>
    <head>
        <h5>
        <a href="{{route('logout')}}">LOGOUT</a> &nbsp;||&nbsp;
        <a href="{{url()->previous()}}">BACK</a> &nbsp;||&nbsp;
        <a href="{{route('manager.home')}}">HOME</a> &nbsp;||&nbsp;
        {{-- <td><a href="{{route('manager.profile',['id'=>Session()->get('logged.manager')])}}">Profile</td> --}}
        </h5><br>
    </head>
    <body>
        @yield('content')
    </body>
</html>