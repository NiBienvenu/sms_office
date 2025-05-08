<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home')}}">
        <div class="sidebar-brand-icon">
            <img style="border-radius: 15px" width="50px" height="50px" src="{{ asset('logo/logo.png')}}" alt="">
        </div>
        <div class="sidebar-brand-text mx-3">{{ env('APP_NAME')}}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home')}}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Users -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index')}}">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Nav Item - Academic Year -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('academic-years.*') ? 'active' : '' }}" href="{{ route('academic-years.index')}}">
            <i class="fas fa-calendar-alt"></i>
            <span>Academic Years</span>
        </a>
    </li>

    <!-- Nav Item - Departments -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('class-rooms.*') ? 'active' : '' }}" href="{{ route('class-rooms.index')}}">
            <i class="fas fa-building"></i>
            <span>Class Room</span>
        </a>
    </li>
    <!-- Nav Item - Departments -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index')}}">
            <i class="fas fa-building"></i>
            <span>Departments</span>
        </a>
    </li>

    <!-- Nav Item - Students -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index')}}">
            <i class="fas fa-user-graduate"></i>
            <span>{{ __('message.student')}}</span>
        </a>
    </li>

    <!-- Nav Item - Teachers -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index')}}">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Teachers</span>
        </a>
    </li>

    <!-- Nav Item - Courses -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index')}}">
            <i class="fas fa-book"></i>
            <span>Courses</span>
        </a>
    </li>

    <!-- Nav Item - Subjects -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index')}}">
            <i class="fas fa-book-open"></i>
            <span>Subjects</span>
        </a>
    </li>

    <!-- Nav Item - Schedule -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('schedules.*') ? 'active' : '' }}" href="{{ route('schedules.index')}}">
            <i class="fas fa-calendar-check"></i>
            <span>Schedule</span>
        </a>
    </li>
    <!-- Nav Item - Schedule -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('grades.*') ? 'active' : '' }}" href="{{ route('grades.index')}}">
            <i class="fas fa-calendar-check"></i>
            <span>Student Grade</span>
        </a>
    </li>
    <!-- Nav Item - Bulletin -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('bulletins.*') ? 'active' : '' }}" href="{{ route('bulletins.index')}}">
            <i class="fas fa-calendar-check"></i>
            <span>Bulletin Management</span>
        </a>
    </li>

    <!-- Nav Item - Payments -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index')}}">
            <i class="fas fa-money-bill-wave"></i>
            <span>Payments</span>
        </a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Components -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-tools"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="{{ route('buttons')}}">Buttons</a>
                <a class="collapse-item" href="{{ route('cards')}}">Cards</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-cogs"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="{{ route('utilities-color')}}">Colors</a>
                <a class="collapse-item" href="{{ route('utilities-border')}}">Borders</a>
                <a class="collapse-item" href="{{ route('utilities-animation')}}">Animations</a>
                <a class="collapse-item" href="{{ route('utilities-other')}}">Other</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-file-alt"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="{{ route('login')}}">Login</a>
                <a class="collapse-item" href="{{ route('register')}}">Register</a>
                <a class="collapse-item" href="{{ route('password.request')}}">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="{{ route('404') }}">404 Page</a>
                <a class="collapse-item" href="{{ route('blank') }}">Blank Page</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('charts') ? 'active' : '' }}" href="{{ route('charts')}}">
            <i class="fas fa-chart-line"></i>
            <span>Charts</span>
        </a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('tables')}}">
            <i class="fas fa-table"></i>
            <span>Tables</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
