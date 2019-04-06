<nav class="navbar navbar-expand-lg navbar-light">
    <h1 class="h1-logo">
        <a class="navbar-brand logo" href="{{config('ziroom.home_url')}}">
            <img src="{{asset('ziroom/images/logo.png')}}" alt="">
        </a>
    </h1>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    北京
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">北京</a>
                </div>
            </li>
            @foreach($nav_data as $v)
                <li class="nav-item
                @if($v['url'] == request()->path())
                    active
                @endif
                ">
                    <a class="nav-link" href="{{url($v['url'])}}">
                        {{$v['name']}} <span class="sr-only">(current)</span>
                    </a>
                </li>

            @endforeach
        </ul>
        <div class="form-inline my-2 my-lg-0 nav-user-right">
            @auth
                <a href="">
                    已登录
                </a>
                <a href="{{route('ziroom_user_logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出</a>
            @else
                <a href="{{route('ziroom_user_login')}}">登录</a>
                <a href="{{route('ziroom_user_register')}}">注册</a>
            @endauth

            <form id="logout-form" action="{{ route('ziroom_user_logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

        </div>
    </div>
</nav>