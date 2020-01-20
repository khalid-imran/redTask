@extends('layout.dashStraucture')

@section('resources')

@endsection

@section('content')
    <div class="inner-content pt-1">
        <div class="row">
            <div class="col-12">
                <div class="font-ash pl-2 font-weight-bold mb-4">{{$project['title']}}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="list-box">
                    <div class="head-bar pb-3">
                        <div class="float-left font-weight-bold">To-do</div>
                        <button class="float-right btn btn-add-card" data-target="#addProjectCard" data-toggle="modal"><i class="fa fa-plus"></i> <span>Add a Card</span></button>
                    </div>
                    @foreach($card as $eachcard)
                        <div class="main-box">
                            <a href="{{route('index.projects.single.card', [$eachcard['id']])}}" style="text-decoration: none" class="each-item mb-3 font-ash">
                                <div>{{$eachcard['title']}}</div>
                            </a>
                            <div class="btn-grp">
                                <span onclick="openEditModal({{json_encode($eachcard)}})"><i class="fa fa-pencil color-edit"></i></span> &nbsp; &nbsp;
                                <span onclick="openDeleteModal({{json_encode($eachcard)}})"><i class="fa fa-trash color-delete"></i> </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-3">
                <div class="list-box">
                    <div class="head-bar pb-3">
                        <div class="float-left font-weight-bold">Doing</div>
                    </div>
                    <div class="each-item mb-3">
                        <div>Front-end</div>
                    </div>
                    <div class="each-item mb-3">
                        <div>Database</div>
                    </div>
                    <div class="each-item mb-3">
                        <div>Server Setting</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="list-box">
                    <div class="head-bar pb-3">
                        <div class="float-left font-weight-bold">Testing</div>
                    </div>
                    <div class="each-item">
                        <div>Sectioin 3</div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="list-box">
                    <div class="head-bar pb-3">
                        <div class="float-left font-weight-bold">Completed</div>
                    </div>
                    <div class="each-item">
                        <div>Front-end</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Add Modal -->
    <div class="modal fade" id="addProjectCard" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card')}}" method="post">
                    {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Card</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="{{$project['id']}}" name="project_id">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Title" name="title"  value="{{old('title')}}">
                            @if($errors->has('title'))<small class="text-danger">{{$errors->first('title')}}</small>@endif
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <textarea name="description" id="" cols="30" rows="8" class="form-control" placeholder="Description"></textarea>--}}
{{--                        </div>--}}
{{--                         <div class="form-group">--}}
{{--                             <div class="mb-3">Attachment</div>--}}
{{--                             <div id="img_view">--}}

{{--                             </div>--}}
{{--                            <input type="file" onchange="uploadImg(event)" name="input_img" class="btn btn-ash">--}}
{{--                         </div>--}}
{{--                        <input type="hidden" id="media_id" name="media_id">--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--Edit Modal -->
    <div class="modal fade" id="editProjectCard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card.edit')}}" method="post">
                    {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Card</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="card_id">
                        <div class="form-group">
                            <input type="text" value="{{old('edit_title')}}" class="form-control" placeholder="Title" name="edit_title">
                            @if($errors->has('edit_title'))<small class="text-danger">{{$errors->first('edit_title')}}</small>@endif
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <textarea name="edit_description" id="" cols="30" rows="8" class="form-control" placeholder="Description"></textarea>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <div class="mb-3">Attachment</div>--}}
{{--                            <button class="btn btn-ash">Add an attachment</button>--}}
{{--                        </div>--}}
{{--                    <input type="hidden">--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--delete Modal -->
    <div class="modal fade" id="deleteCardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card.delete')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Card</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="delete_id">
                        <div>Are you sure?</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>

        function openEditModal(data) {
         $('input[name=edit_title]').val(data.title);
         $('textarea[name=edit_description]').val(data.description);
         $('#id').val(data.id);
         $('#editProjectCard').modal('show')
        }

        function openDeleteModal(data) {
            $('input[name=delete_id]').val(data.id);
            $('#deleteCardModal ').modal('show')
        }

        let img = '';
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
                        let file = res.data.file_name;
                        let file_id = res.data.id;
                        let eImg = $('#media_id').val();
                        eImg = eImg.split(',');
                        eImg.push(file_id);
                        $('#media_id').val(eImg.join(','));
                        img += '<img class="add-img" src="{{asset("images")}}/'+file+'" alt="">'
                        $('#img_view').html(img);

                    }
                }

            });
        }
    </script>

    @if($errors->has('title'))
        <script>
            $(function () {
                $('#addProjectCard').modal('show');
            })
        </script>
    @elseif($errors->has('edit_title'))
        <script>
            $(function () {
                $('#editProjectCard').modal('show');
            })
        </script>
    @endif
@endsection


