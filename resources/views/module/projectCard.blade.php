@extends('layout.dashStraucture')

@section('resources')

@endsection

@section('content')
    <div class="inner-content pt-1" id="app">
        <div class="row">
            <div class="col-md-10">
                <div class="font-ash pl-2 font-weight-bold mb-4">{{$card['title']}}</div>
                <div class="margin-10"></div>
                @if($card['desc'] !== null)
                    <h5 class="h-5"><i class="fa fa-bars"></i> Description</h5>
                <div class="desc-text-card">
                    <p class="font-ash">{{$card['desc']['description']}}</p>
                    <div class="float-right">
                        <button class="btn btn-purple" style="padding: 0px 5px;"  data-toggle="modal" data-target="#ModalDescEdit"><i class="fa fa-pencil"></i> Edit</button>
                        <button class="btn btn-danger" style="padding: 0px 5px;" data-toggle="modal" data-target="#ModalDescDelete"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                </div>
                @endif
                <div class="margin-10"></div>
                <div class="margin-10"></div>
                @if(count($card['task']) > 0)
                    <div class="desc-text">
                        <h5 class="h-5"><i class="fa fa-building"></i> Task List</h5>
                        @foreach($card['task'] as $index=>$task)
                        <div class="taskList {{$task['status'] ? 'success' : ''}}">
                            <div>
                                <input style="vertical-align: middle" type="checkbox" {{$task['status'] ? 'checked' : ''}} @change="statusCheck($event, {{$task['id']}})" >
                                <span class="text-style  {{$task['status'] ? 'line-finish' : ''}}">{{$task['name']}}</span>
                                <span class="float-right">
                                <button style="padding: 0px 5px;" class="btn btn-login" @click="taskEdit({{json_encode($task)}})"><i class="fa fa-pencil"></i></button>&nbsp;
                                <button style="padding: 0px 5px;"  class="btn btn-danger" @click="taskDelete({{$task['id']}})"><i class="fa fa-trash"></i></button>
                            </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
                <div class="margin-10"></div>
                <div class="margin-10"></div>

                <div class="desc-text">
                    <h5 class="h-5"> <i class="fa fa-camera-retro"></i> Media</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <img width="100%" src="" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center">
                @if($card['desc'] === null)
                    <button class="btn btn-purple" style="width: 162px;" data-toggle="modal" data-target="#ModalDesc"><i class="fa fa-clipboard"></i> Add Description </button>
                @endif
                <div class="margin-10"></div>
                <button class="btn btn-info" style="width: 162px;" data-toggle="modal" data-target="#taskAdd"><i class="fa fa-th-large"></i> &nbsp;Add Task </button>
                <div class="margin-10"></div>
                    <label class="btn btn-primary" style="width: 162px;" for="addMedia"><i class="fa fa-camera"></i> &nbsp;Add Media</label>
                    <input id="addMedia" type="file" @change="uploadFile($event)" hidden style="width: 162px;">
            </div>
        </div>
    </div>
    {{--task modal--}}
    <!--create task Modal -->
    <div class="modal fade" id="taskAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card.task')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" placeholder="Task name">
                        <input type="hidden" name="card_id" value="{{$card['id']}}">
                        <input type="hidden" name="status" value="0">
                        {{csrf_field()}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--update task Modal -->
    <div class="modal fade" id="taskEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card.task.edit')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name"  id="task_name" class="form-control" placeholder="Task name">
                        <input type="hidden" name="id" id="task_id">
                        {{csrf_field()}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--delete task Modal -->
    <div class="modal fade" id="taskDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card.task.delete')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="id" id="delete_task_id">
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
    {{--task modal end--}}
    <!--create desc Modal -->
    <div class="modal fade" id="ModalDesc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card.desc')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Description</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <textarea name="desc" id="" class="form-control" placeholder="Add description" cols="30" rows="10"></textarea>
                        <input type="hidden" name="card_id" value="{{$card['id']}}">
                        {{csrf_field()}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--update desc Modal -->
    <div class="modal fade" id="ModalDescEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card.desc.edit')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Description</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($card['desc'] !== null)
                            <textarea name="desc" class="form-control" placeholder="Add description" cols="30" rows="10" >{{$card['desc']['description'] }}</textarea>
                            <input type="hidden" name="id" value="{{$card['desc']['id'] }}">
                        @endif
                        {{csrf_field()}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--delete desc Modal -->
    <div class="modal fade" id="ModalDescDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.card.desc.delete')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Card</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        @if($card['desc'] !== null)
                            <input type="hidden" value="{{$card['desc']['id']}}" name="id">
                        @endif
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
        new Vue({
            el: "#app",
            data: {},
            methods: {
                updateStatus: function (id, status) {
                    let THIS = this;
                    $.ajax({
                        type: "post",
                        url: "{{route('index.projects.card.task.status')}}",
                        data: {
                            id: id,
                            status: status,
                            _token: '{{csrf_token()}}'
                        },
                        success: function (res) {
                            if (res.status === 200) {
                                location.reload(true);
                            }
                        }

                    });
                },
                statusCheck: function (e, i) {
                    if ($(e.target).prop('checked')) {
                        let status = 1;
                        this.updateStatus(i, status)
                    } else {
                        let status = 0;
                        this.updateStatus(i, status)
                    }
                },
                taskEdit: function (task) {
                    $('#task_id').val(task.id);
                    $('#task_name').val(task.name);
                    $('#taskEdit').modal('show');
                },
                taskDelete: function (id) {
                    $('#delete_task_id').val(id);
                    $('#taskDelete').modal('show');
                },
                uploadFile: function (event) {
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

                            }
                        }

                    });
                }
            },
            created(){

            },
            mounted() {

            },

        })
    </script>
@endsection


