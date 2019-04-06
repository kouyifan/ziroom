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
    <h1>register</h1>
    <form action="{{route('ziroom_user_register_post')}}" method="post">
        @csrf
        <div class="form-group needs-validation">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" value="{{old('email')}}">
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Enter username" value="{{old('name')}}">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" value="">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">RePassword</label>
            <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        @include('ziroom::tips.error')
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection

@section('script')

@endsection