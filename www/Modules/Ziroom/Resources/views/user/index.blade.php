@extends('ziroom::layouts.master')

@section('title', '北京租房')

@section('styles')
    <link rel="stylesheet" href="{{ mix('ziroom/css/home.css') }}">
    <style>
        h1{
            margin: 20px;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <h1>Login</h1>
    <form action="{{route('ziroom_user_login_post')}}" method="post">
        @csrf
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input"  name="remember" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        @include('ziroom::tips.error')
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection

@section('script')

@endsection