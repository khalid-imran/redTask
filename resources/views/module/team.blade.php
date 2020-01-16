@extends('layout.dashStraucture')

@section('resources')

@endsection

@section('content')
    <div class="inner-content">
        <div class="row">
            @foreach($team as $eachTeam)
            <div class="col-2">
                <div class="parent-box">
                    <a href="{{route('index.team.detail', [$eachTeam['id']] )}}">
                        <div class="project-box">
                            <div class="name">
                                <div class="float-left">{{$eachTeam['title']}}</div>
                            </div>
                        </div>
                    </a>
                    <div class="float-right option-btn">
                            <span onclick="openEditModal({{json_encode($eachTeam)}})"><i
                                        class="fa fa-pencil color-edit"></i></span> &nbsp; &nbsp;
                        <span onclick="openDeleteModal({{json_encode($eachTeam)}})" ><i class="fa fa-trash color-delete"></i> </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!--Add Modal -->
    <div class="modal fade" id="addTeamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.team.create')}}" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Team Name" name="title" value="{{ old('title') }}">
                            @if($errors->has('title'))<span class="text-danger">{{$errors->first('title')}}</span>@endif
                        </div>
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
    <div class="modal fade" id="editTeamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.team.edit')}}" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Team Name" name="edit_title">
                            <input type="hidden" name="id">
                            @if($errors->has('title'))<span class="text-danger">{{$errors->first('title')}}</span>@endif
                        </div>
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
    <div class="modal fade" id="deleteTeamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.team.delete')}}" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <div>Are you sure?</div>
                    <input type="hidden"name="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    @if( old('title') )
        <script>
            $(function () {
                $('#addTeamModal').modal('show');
            })
        </script>
    @endif

    <script>
        function openEditModal(data) {
            $('input[name="edit_title"]').val(data.title);
            $('input[name="id"]').val(data.id);
            $('#editTeamModal').modal('show');
        }
        function openDeleteModal(data) {
            $('input[name="delete_id"]').val(data.id);
            $('#deleteTeamModal').modal('show');
        }
    </script>
@endsection


