@extends('ziroom::layouts.master')

@section('title', '栏目页')

@section('styles')

@endsection

@section('content')
    <div class="list-wrap">
        <div class="node_infor area">
            <a href="{{config('ziroom.home_url')}}">首页</a>
            <span class="org">&gt;</span>
            <a href="javascript:void(0);">自如找房</a>
        </div>
        <div class="top-search-wrap">
            <div class="top-search">
                <input type="text" value="" id="i_q_keyword_1" class="txt  textipt" placeholder="请输入区域、地铁、小区名开始找房...">
                <input name="1" class="btn btn_sub" type="button">
            </div>
        </div>
        <div class="top-search-container">
            <ul>
                <li>
                    <div class="left-label">
                        <span>类型:</span>
                    </div>
                    <div class="li-cont">
                        <a href="" class="active">不限</a>
                        <a href="">友家合租</a>
                        <a href="">自如整租</a>
                        <a href="">业主直租</a>
                        <a href="">自如豪宅</a>
                    </div>
                </li>
                <li class="li-has-son li-area">
                    <div class="left-label">
                        <span>区域:</span>
                    </div>
                    <div class="li-cont">
                        <a href="" class="active">不限</a>
                        @foreach($area_data as $area)
                            <a href="" class="a-parent" data-index="{{$loop->index}}">{{$area['name']}}</a>
                            <div class="dropdown-box dropdown-cont-{{$loop->index}}">
                                @foreach($area['sons'] as $son)
                                    <a href="">{{$son['name']}}</a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </li>
                <li class="li-has-son li-subway">
                    <div class="left-label">
                        <span>地铁:</span>
                    </div>
                    <div class="li-cont">
                        <a href="" class="active">不限</a>
                        @foreach($subway_data as $subway)
                            <a href="" class="a-parent" data-index="{{$loop->index}}">{{$subway['name']}}</a>
                            <div class="dropdown-box dropdown-cont-{{$loop->index}}">
                                @foreach($subway['sons'] as $son)
                                    <a href="">{{$son['name']}}</a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </li>
                <li>
                    <div class="left-label">
                        <span>租金:</span>
                    </div>
                    <div class="li-cont">
                        <a href="" class="active">不限</a>
                        <a href="">1500元以下</a>
                        <a href="">1500~2000元</a>
                        <a href="">2000~3000元</a>
                        <a href="">3000~5000元</a>
                        <a href="">5000~6500元</a>
                        <a href="">6500~8000元</a>
                        <a href="">8000元以上</a>
                    </div>
                </li>
                <li>
                    <div class="left-label">
                        <span>居室:</span>
                    </div>
                    <div class="li-cont">
                        <a href="" class="active">不限</a>
                        <a href="">1居</a>
                        <a href="">2居</a>
                        <a href="">3居</a>
                        <a href="">4居</a>
                        <a href="">4居以上</a>
                    </div>
                </li>
                <li>
                    <div class="left-label">
                        <span>面积:</span>
                    </div>
                    <div class="li-cont">
                        <a href="" class="active">不限</a>
                        <a href="">40㎡以下</a>
                        <a href="">40㎡~60㎡</a>
                        <a href="">60㎡~80㎡</a>
                        <a href="">80㎡~100㎡</a>
                        <a href="">100㎡~120㎡</a>
                        <a href="">120㎡以上</a>
                    </div>
                </li>
                <li class="div-housing_features">
                    <div class="left-label">
                        <span>特色:</span>
                    </div>
                    <div class="li-cont">
                        <div class="div-orientation">
                            <select class="form-control select-orientation" name="orientation" id="orientation">
                                <option value="0" selected>朝向</option>
                                <option>不限</option>
                                <option>东</option>
                                <option>南</option>
                                <option>西</option>
                                <option>北</option>
                                <option>东南</option>
                                <option>西南</option>
                            </select>
                        </div>
                        <div class="form-check ten_minutes_underground">
                            <input class="form-check-input" type="checkbox" name="ten_minutes_underground"
                                   id="ten_minutes_underground" value="">
                            <label class="form-check-label" for="ten_minutes_underground">
                                地铁十分钟
                            </label>
                        </div>

                    </div>
                </li>
            </ul>
        </div>
        <div class="rooms-div">
            @foreach($rooms as $room)
                <div class="room-box">
                    <div class="room-cont">
                        <div class="room-thumb">
                            <a href="{{route('ziroom_room_detail',['id'=>$room->id])}}" target="_blank">
                                <img src="{{$room->thumb}}" alt="">
                            </a>
                        </div>
                        <div class="room-detail-wrap">
                            <div class="a-title">
                                <a target="_blank" href="{{route('ziroom_room_detail',['id'=>$room->id])}}"
                                   class="title">{{$room->name}}</a>
                            </div>
                            <div class="housing_detection">
                                @foreach($room->housing_detection as $house_detection)
                                    <span>{{$house_detection}}</span>
                                @endforeach
                            </div>
                            <div class="detail">
                                <div class="detail-cont">
                                    <div class="detail-a">
                                        <span>{{$room->measure_area}}㎡ |</span>
                                        <span>{{$room->floor}} |</span>
                                        <span>{{$room->house_type}}</span>
                                    </div>
                                    {{--<div class="detail-b">--}}
                                    {{--有2间空房--}}
                                    {{--</div>--}}
                                    <div class="detail-c">
                                        {{$room->traffic['0']??''}}
                                    </div>
                                </div>
                                <div class="detail-features">
                                    @foreach($room->housing_features as $house_feature)
                                        <span>{{$house_feature}}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="room-price-wrap">
                            <div class="room-price-wrap-cont">
                                <span class="price">
                                    ￥ {{$room->month_price}}
                                </span>
                                <span class="month">(每月)</span>
                            </div>
                            <a href="{{route('ziroom_room_detail',['id'=>$room->id])}}" class="view-more" target="_blank">
                                查看更多
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div id="pagination">
            {{$rooms->links()}}
        </div>
    </div>
@endsection

@section('script')
    <script>
      $(function () {
        var li_cont_top = $('.li-cont').height();

        $('.li-has-son .a-parent').on('mouseover', function () {
          var _this = $(this);
          var _index = _this.data('index');
          if (_index > 14) {
            var li_cont_top_in = li_cont_top * 2 + 5;
            _this.parents('.li-has-son').find('.dropdown-box').eq(_index).css('left', '0');
          } else {
            var li_cont_top_in = li_cont_top + 5;
          }

          _this.parents('.li-has-son').find('.dropdown-box').eq(_index).css('top', li_cont_top_in);
          _this.parents('.li-has-son').find('.dropdown-box').hide().eq(_index).show();

        });
        $('.li-has-son').on('mouseleave', function () {
          $('.dropdown-box').hide();
        });
      })
    </script>
@endsection