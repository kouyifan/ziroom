@extends('ziroom::layouts.master')

@section('title', '北京租房')

@section('styles')
    <link rel="stylesheet" href="{{ mix('ziroom/css/home.css') }}">
@endsection

@section('home_slide')
    <div id="slides">
        <img src="{{asset('ziroom/images/home_slide/1.jpg')}}" alt="">
        <img src="{{asset('ziroom/images/home_slide/2.jpg')}}" alt="">
        <img src="{{asset('ziroom/images/home_slide/3.jpg')}}" alt="">
        <a href="" class="slidesjs-previous slidesjs-navigation left"><i class="fas fa-angle-left"></i></a>
        <a href="" class="slidesjs-next  slidesjs-navigation right"><i class="fas fa-angle-right"></i></a>
    </div>
@endsection

@section('content')

@endsection

@section('script')
    <script src="{{asset('ziroom/extends/slidejs/jquery.slides.min.js')}}"></script>
    <script>
        $(function () {
            var slide_width = $(window).width();
            var slide_height = slide_width * (600/1920);

            $("#slides").slidesjs({
                width: slide_width,
                height:slide_height,
                start: 1,
                play: {
                    effect: "fade",　　//切换方式，跟上面两个切换方式不冲突；默认值slide
                    interval: 5000,　　　//在每一个幻灯片上花费的时间；默认值5000
                    pauseOnHover: true,　　//鼠标移入是否暂停；默认值false
                    restartDelay: 2500,　　//重启延迟；默认值2500
                    auto: true,
                },
                navigation: {
                    active: false,　　//显示或隐藏前一张后一张切换按钮；默认值为true,
                    effect: "fade"　　//设置切换的方式，slide:平滑，fade:渐显；默认值slide
                },
                pagination:{
                    active: false,
                }
            });
            $('.slidesjs-navigation').hide();
            $('#slides').hover(function(){
                $('.slidesjs-navigation').stop(true).fadeIn();
            },function(){
                $('.slidesjs-navigation').stop(true).fadeOut();
            });
        })
    </script>
@endsection