@extends('layout.dashStraucture')

@section('resources')

@endsection

@section('content')
    <div class="inner-content pt-1">
        <div class="row">
            <div class="col-12">
                <div class="font-ash pl-2 font-weight-bold mb-5"><span class="text-danger">Team Name:</span> {{$team['title']}}</div>
            </div>
        </div>
        <div class="container">
            @foreach($members as $member)
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-1">
                                    @if($member['avatar'] != '')
                                    <img class="rounded-circle mr-5 border-img" width="50px" height="50px" src="{{asset('images/'.$member['avatar'])}}" alt="">
                                    @endif
                                    @if($member['avatar'] == '')
                                    <img class="rounded-circle mr-5 border-img" width="50px" height="50px" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmMh_7zL28-d3bYfiEuySLpEar1EHrf25hHI9SkmXlN6jC5Y-P&s" alt="">
                                    @endif
                                </div>
                                <div class="col-8 pl-4">
                                    <div class="name">{{$member['first_name'].' '.$member['last_name']}}</div>
                                    <div class="font-ash"><span>@</span>{{$member['username']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-ash" onclick="openDeleteModal({{json_encode($member)}})"> <i class="fa fa-times"></i> Remove</button>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border mt-2 mb-3"></div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    <!--Add Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.team.add')}}" method="post">
                    {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <input type="hidden" name="team_id" value="{{$team_id}}">
                        <select class="js-example-basic-multiple form-control" style="width: 100%" name="users[]" multiple="multiple">
                            @foreach($users as $user)
                                <option value="{{$user['id']}}">{{$user['first_name']}}</option>
                            @endforeach
                        </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--delete Modal -->
    <div class="modal fade" id="deleteMemberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('index.team.add.delete')}}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Member</h5>
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
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Select Member"
            });
        });

        function openDeleteModal(data) {
            $('input[name =  delete_id]').val(data.id);
            $('#deleteMemberModal').modal('show');
        }
    </script>
@endsection


