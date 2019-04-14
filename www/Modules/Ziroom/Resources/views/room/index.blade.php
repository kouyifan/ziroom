@extends('ziroom::layouts.master')

@section('title', '栏目页')

@section('styles')

@endsection

@section('content')
    <div class="room-wrap">
        <div class="top-meta">
            <a href="{{config('ziroom.home_url')}}">首页</a> > {{$room->name}}
        </div>

    </div>
    <div id="lightgallery">
        @foreach($room->img_lists as $img)
            <a href="{{$img->img_path}}">
                <img src="{{$img->img_path}}">
            </a>
        @endforeach
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/combine/npm/lightgallery,npm/lg-autoplay,npm/lg-fullscreen,npm/lg-hash,npm/lg-pager,npm/lg-share,npm/lg-thumbnail,npm/lg-video,npm/lg-zoom"></script>
    <script>

      $(function () {
        $("#lightgallery").lightGallery();
      })
    </script>
@endsection