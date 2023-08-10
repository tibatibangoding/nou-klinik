<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nama  }} |</span>
                @if (Auth::user()->roles->pluck('name')[0] == 'dokter')
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ \Illuminate\Support\Str::ucfirst(Auth::user()->roles->pluck('name')[0]) }} {{ \Illuminate\Support\Str::ucfirst(Auth::user()->poli->pluck('nama_poli')[0]) }}</span>
                @else
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->roles->pluck('name')[0]  }}</span>
                @endif
                <img class="img-profile rounded-circle"
                    src="{{ url('backend/img/undraw_profile.svg') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary" type="submit">Log Out</button>
                </form>
            </div>
        </li>

    </ul>

</nav>
