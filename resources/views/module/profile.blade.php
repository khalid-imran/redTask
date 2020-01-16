@extends('layout.dashStraucture')

@section('resources')

@endsection

@section('content')
    <div class="inner-content">
        <div class="profile-container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <img src="https://a.trellocdn.com/prgb/dist/images/member-home/taco-privacy.ced1e262c59e0225e3aa.svg" alt="">
                </div>
                <div class="col-12 mb-4">
                    <h2>Manage your personal information</h2>
                    <div>Control which information people see and Power-Ups may access. To learn more, view our Terms of Service or Privacy Policy.</div>
                </div>
                {{--about--}}
                <div class="col-12 mb-1">
                    <div class="font-weight-bold font-ash">About</div>
                </div>
                <div class="col-12 mb-4">
                    <div class="border"></div>
                </div>
                <div class="col-12">
                    <form action="{{route('index.profile.update')}}" method="post">
                        <div class="row">
                            <div class="col-8">
                                {{csrf_field()}}
                                <input type="hidden" id="avatar" name="avatar">
                                <div class="form-group">
                                    <label for="">First name</label>
                                    <input type="text" name="first_name" class="form-control" value="{{auth()->user()->first_name}}">
                                    @if($errors->has('first_name'))<small class="text-danger text-left">{{$errors->first('first_name')}}</small>@endif
                                </div>
                                <div class="form-group">
                                    <label for="">Last name</label>
                                    <input type="text" name="last_name" class="form-control"  value="{{auth()->user()->last_name}}">
                                    @if($errors->has('last_name'))<small class="text-danger text-left">{{$errors->first('last_name')}}</small>@endif
                                </div>
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input type="text" name="username" class="form-control"  value="{{auth()->user()->username}}">
                                    @if($errors->has('username'))<small class="text-danger text-left">{{$errors->first('username')}}</small>@endif
                                </div>
                                <br>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success w-100">Update</button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="profile-picture text-center">
                                    @if(auth()->user()->avatar != '')
                                        <img id="img_display" style="margin-top: 30px" height="160px" width="160px" class="border-img rounded-circle" src="{{asset('images/'.auth()->user()->avatar)}}" alt="">
                                    @endif
                                        <div id="img"></div>
                                    <button class="btn btn-ash mt-3 file_picker"><span>Change Picture</span>
                                        <input onchange="uploadImg(event)" accept="image/*" id="img_input" name="input_img" type="file" class="select_file">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                    <br>
                </div>
                {{--contact--}}
                <div class="col-12 mb-1">
                        <div class="font-weight-bold font-ash ">Contact</div>
                </div>
                <div class="col-12 mb-4">
                    <div class="border"></div>
                </div>
                <div class="col-12">
                    <form action="{{route('index.profile.email')}}" method="post">
                        <div class="form-group">
                            {{csrf_field()}}
                            <label for="">Email Address</label>
                            <input type="text" name="email" class="form-control" value="{{auth()->user()->email}}">
                        </div>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success w-100">Update</button>
                        </div>
                    </form>
                    <br>
                    <br>
                </div>
                {{--password--}}
                <div class="col-12 mb-1">
                    <div class="font-weight-bold font-ash">Password</div>
                </div>
                <div class="col-12 mb-4">
                    <div class="border"></div>
                </div>
                <div class="col-12">
                    <form action="{{route('index.profile.password')}}" method="post">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="">Old password</label>
                            <input type="password" name="old_password" class="form-control">
                            @if($errors->has('old_password'))<small class="text-danger text-left">{{$errors->first('old_password')}}</small>@endif
                        </div>
                        <div class="form-group">
                            <label for="">New password</label>
                            <input type="password" name="password" class="form-control">
                            @if($errors->has('password'))<small class="text-danger text-left">{{$errors->first('password')}}</small>@endif
                        </div>
                        <div class="form-group">
                            <label for="">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                            @if($errors->has('password_confirmation'))<small class="text-danger text-left">{{$errors->first('password_confirmation')}}</small>@endif
                        </div>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let avatar = '';
        let img = '';
        let img1 = '';
        function uploadImg(event) {
            let trigger = $(event.target);
            let input = event.target.files[0];
            let formData = new FormData();
                formData.append("input_img", input);
                formData.append("_token", '{{csrf_token()}}');
            $.ajax({
                type: "POST",
                url: "{{route('index.media')}}",
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            let percentComplete = (evt.loaded / evt.total) * 100;
                            console.log(percentComplete);
                        }
                    }, false);
                    return xhr;
                },
                success: function (res) {
                    if (parseInt(res.status) === 2000) {
                        avatar = res.data.file_name;
                        $('#img').html(" ");
                        $('#img_input').val('');
                        $('#avatar').val(avatar);
                        img = '';
                        img += `<img class="rounded-circle border-img" style="margin-top: 30px" height="160px" width="160px" src="{{asset("images")}}/`+avatar+`" alt="img">`;
                        $('#img_display').css('display','none');
                        $('#img').html(img);
                    }
                }

            });
        }
        @if(auth()->user()->avatar == '')
        $(function() {
            if (avatar.length == ''){
                img1 += `<img class="rounded-circle w-100" style="margin-top: 30px" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmMh_7zL28-d3bYfiEuySLpEar1EHrf25hHI9SkmXlN6jC5Y-P&s" alt="">`;
                $('#img').html(img1);
            }
        });
        @endif
    </script>
@endsection


