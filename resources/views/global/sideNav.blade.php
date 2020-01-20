<nav class="side-bar">
    <section class="side-bar-content">
        <ul class="nav-bar">
            <li class="{{$page === 'dashboard'? 'active' : ''}}">
                <a href="{{route('index.dashboard')}}">
                    <i class="fa fa-tachometer"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{$page === 'projects' || $page === 'projectsSingle'|| $page === 'projectCardSingle' ? 'active' : ''}}">
                <a href="{{route('index.projects')}}">
                    <i class="fa fa-clone"></i>
                    <span>Projects</span>
                </a>
            </li>
        </ul>
    </section>
</nav>
