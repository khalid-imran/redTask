@extends('layout.structure')

@section('resources')

@endsection

@section('content')

    <div class="main-wrapper">
        <nav class="nav-bar pb-5">
            <div class="row p-3">
                <div class="col-6 d-flex align-items-center">
                    <img class="w-180px pr-1" src="{{ asset('img/redtask.png') }}" alt="task">
                </div>
                <div class="col-6 text-right">
                    <button class="btn btn-login mr-3"><a href="{{route('index.login')}}">Login</a></button>
                    <button class="btn btn-sign"><a href="{{route('index.signUp')}}">Sign Up</a></button>
                </div>
            </div>
        </nav>
        <div class="hero-section">
            <div class="row p-3">
                <div class="col-5 d-flex align-items-center">
                    <div class="desc-text">
                        <div class="text-1 pb-2">Welcome to RedTask</div>
                        <div class="text-2 pb-3">Control Your Business Trust us</div>
                        <div class="text-3 pb-5">RedTask lets you work more collaboratively and get more done.
                            RedTask’s boards, lists, and cards enable you to organize and prioritize your projects in a fun, flexible, and rewarding way.</div>
                        <button class="btn btn-sign2"> <a href="{{route('index.signUp')}}">Sign Up - its Free !</a></button>
                    </div>
                </div>
                <div class="col-7 d-flex align-items-center">
                    <img class="w-100" src="{{ asset('img/home.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>

@endsection


