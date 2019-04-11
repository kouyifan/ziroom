<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Ziroom - @yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token()}}">

        <link rel="stylesheet" href="{{ mix('ziroom/css/app.css')}}">
        @yield('styles')
        <styles>

        </styles>
    </head>
    <body>
        <div id="nav">
            <div class="container">
                @include('ziroom::common.nav')
            </div>
        </div>

        @yield('home_slide')

        <div id="app" class="container">
            @yield('content')
            @include('ziroom::common.footer')
        </div>

    </body>

    {{--script--}}
    <script src="{{ mix('ziroom/js/app.js') }}"></script>
    @yield('script')
    <script>
        $(function () {
            $(".dropdown").mouseover(function () {
                $(this).click();
            });

            $(".dropdown").mouseleave(function(){

            });
        })
    </script>
    {{--script--}}
</html>
