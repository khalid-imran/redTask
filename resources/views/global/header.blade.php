<div class="header position-fixed" style="z-index: 10">
    <div class="row no-row">
        <div class="col-6 d-flex align-items-center">
            <a href="{{route('index.dashboard')}}"><img width="180px" class="pr-1" src="{{ asset('img/redtask.png') }}" alt="task"></a>
        </div>
        <div class="col-6 text-right">
            @if($page == 'projects')
            <button class="btn btn-add" data-toggle="modal" data-target="#addProjectModal"><i class="fa fa-plus"></i> Add Project</button>
            @endif
            @if($page == 'team')
            <button class="btn btn-add" data-toggle="modal" data-target="#addTeamModal"><i class="fa fa-plus"></i> Add Team</button>
            @endif
            @if($page == 'team detail')
            <button class="btn btn-add" data-toggle="modal" data-target="#addMemberModal"><i class="fa fa-plus"></i> Add Member</button>
            @endif
            @if(auth()->user()->avatar == '')
                    <img width="40px" class="ml-4 rounded-circle" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmMh_7zL28-d3bYfiEuySLpEar1EHrf25hHI9SkmXlN6jC5Y-P&s" alt="">
            @endif
            @if(auth()->user()->avatar != '')
                    <img width="40px" height="40px" class="ml-4 rounded-circle" src="{{asset('images/'.auth()->user()->avatar)}}" alt="">
            @endif

            <div class="dropdown ml-3 mt-2 mr-4 float-right">
                <a class="dropdown-toggle no-style" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{auth()->user()->first_name.' '.auth()->user()->last_name}}
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="{{route('index.profile')}}">Profile</a>
                    <a class="dropdown-item" href="{{route('index.logout')}}">Log out</a>
                </div>
            </div>
        </div>
    </div>
</div>
