@extends('layout.dashStraucture')

@section('resources')

@endsection

@section('content')
    <div class="inner-content">
        <div class="row">
            @foreach($projects as $project)
                <div class="col-2">
                    <div class="parent-box">
                        <a href="{{route('index.projects.single', [$project['id']])}}">
                            <div class="project-box">
                                <div class="name">
                                    <div class="float-left">{{$project['title']}}</div>
                                </div>
                            </div>
                        </a>
                        <div class="float-right option-btn">
                            <span onclick="openEditModal({{json_encode($project)}})"><i class="fa fa-pencil color-edit"></i></span> &nbsp; &nbsp;
                            <span onclick="openDeleteModal({{json_encode($project)}})"><i class="fa fa-trash color-delete"></i> </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!--Add Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.create')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Project Name" name="project_name"
                                   value="{{ old('project_name') }}">
                            @if($errors->has('project_name'))<small
                                    class="text-danger">{{$errors->first('project_name')}}</small>@endif
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Add members by email"
                                   name="add_member">
                        </div>
                        <div class="form-group">
                            <select type="text" class="form-control" name="add_team">
                                <option value="">Select Team</option>
                            </select>
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
    <div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.edit')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Project Name" name="edit_name">
                            <input type="hidden" name="id">
                            @if($errors->has('edit_name'))<small
                                    class="text-danger">{{$errors->first('edit_name')}}</small>@endif
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Add members by email"
                                   name="add_member">
                        </div>
                        <div class="form-group">
                            <select type="text" class="form-control" name="add_team">
                                <option value="">Select Team</option>
                            </select>
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
    <div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.projects.delete')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Project</h5>
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




    @if(old('add'))
        <script>
            $(function () {
                $('#addProjectModal').modal('show');
            })
        </script>
    @elseif(old('edit'))
        <script>
            $(function () {
                $('#editProjectModal').modal('show');
            })
        </script>
    @endif
    <script>
        function openEditModal(data) {
            $('input[name="edit_name"]').val(data.title);
            $('input[name="id"]').val(data.id);
            $('#editProjectModal').modal('show');
        }

        function openDeleteModal(data) {
            $('input[name="delete_id"]').val(data.id);
            $('#deleteProjectModal').modal('show');
        }
    </script>
@endsection


