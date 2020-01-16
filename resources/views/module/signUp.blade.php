@extends('layout.structure')

@section('resources')

@endsection

@section('content')
    <div class="sign-up-wrapper">
        <div class="sign-up-container">
            <div class="row no-row">
                <div class="col-6 text-center">
                    <div class="d-table pt-10rem">
                        <div class="sign-up-text pb-5">Sign Up Below</div>
                        @if($errors->has('success'))<p class="alert alert-success text-center">{{$errors->first('success')}}</p>@endif
                        <form method="post" action="{{route('index.signUp.action')}}">
                            {{csrf_field()}}
                            <div class="form-row">
                                <div class="form-group col-6 pb-5">
                                    <input type="text" name="first_name" class="form-control custom-input" placeholder="First name" value="{{ old('first_name') }}">
                                    @if($errors->has('first_name'))<small class="text-danger text-left">{{$errors->first('first_name')}}</small>@endif
                                </div>
                                <div class="form-group col-6 pb-5">
                                    <input type="text" name="last_name" class="form-control custom-input" placeholder="Last name" value="{{old('last_name')}}">
                                    @if($errors->has('last_name'))<small class="text-danger text-left">{{$errors->first('last_name')}}</small>@endif
                                </div>
                                <div class="form-group col-6 pb-5">
                                    <input type="email"  name="email" class="form-control custom-input" placeholder="Email" value="{{old('email')}}">
                                    @if($errors->has('email'))<small class="text-danger text-left">{{$errors->first('email')}}</small>@endif
                                </div>
                                <div class="form-group col-6 pb-5">
                                    <input type="text"  name="username" class="form-control custom-input" autocomplete="none" placeholder="Username"  value="{{old('username')}}">
                                    @if($errors->has('username'))<small class="text-danger text-left">{{$errors->first('username')}}</small>@endif
                                </div>
                                <div class="form-group col-6 pb-5">
                                    <input type="password"  name="password" autocomplete="new-password" class="form-control custom-input" placeholder="Password">
                                    @if($errors->has('password'))<small class="text-danger text-left">{{$errors->first('password')}}</small>@endif
                                </div>
                                <div class="form-group col-6 pb-5">
                                    <input type="password"  name="password_confirmation" class="form-control custom-input" placeholder="Confirm Password">
                                </div>
                            </div>
                            <button class="btn btn-sign3 w-100" type="submit">Create your account</button>
                            <br>
                            <br>
                            <div class="pb-5">By clicking 'Create Account' you agree to RedTask's <span class="text-1">Term and condition</span></div>
                            <div>Already have account? &nbsp; <span> <a href="{{route('index.login')}}">Login to your account</a></span></div>
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


