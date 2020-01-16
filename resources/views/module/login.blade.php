@extends('layout.structure')

@section('resources')

@endsection

@section('content')
    <div class="sign-up-wrapper">
        <div class="sign-up-container">
            <div class="row no-row">
                <div class="col-6">
                    <div class="pt-15rem">
                        <div class="sign-up-text text-center pb-5">Login Below</div>
                        <form method="post" action="{{route('index.login.action')}}">
                            {{csrf_field()}}
                            <div class="form-row">
                                <div class="form-group col-6 pb-5">
                                    <input type="text" name="email" autocomplete="new-username" class="form-control custom-input" placeholder="Email Address" value="{{ old('email') }}">
                                    @if($errors->has('email'))<span class="text-danger">{{$errors->first('email')}}</span>@endif
                                </div>
                                <div class="form-group col-6 pb-5">
                                    <input type="password" name="password" autocomplete="new-password" class="form-control custom-input" placeholder="Password">
                                    @if($errors->has('password'))<span class="text-danger">{{$errors->first('password')}}</span>@endif
                                </div>
                            </div>
                            <button class="btn btn-sign3 w-100" type="submit">Login</button>
                            <br>
                            <br>
                            <div class="text-center">Don't have an account?  <span> <a href="{{route('index.signUp')}}">Sign Up</a></span></div>
                            <div></div>
                        </form>
                    </div>
                </div>
                <div class="col-6 text-center pt-10rem">
                    <img width="80%" src="{{ asset('img/sign-up.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection


