<ul class="navbar-nav sidebar sidebar-dark accordion text-white" id="accordionSidebar" style="background-color: #0f3556;">
    <!-- Sidebar - Brand -->
    <div class="sidebar-brand d-flex align-items-center justify-content-between text-white">
        <div class="sidebar-brand-content">
            <div class="sidebar-brand-icon">
                <img src="{{ asset('images/admin-genie.png') }}" alt="Admin Genie" style="height: 32px; width: auto;">
            </div>
            <div class="sidebar-brand-text mx-3">Admin Genie</div>
        </div>
        <button class="sidebar-close d-md-none" type="button" aria-label="Close sidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <hr class="sidebar-divider my-0">
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('companies.*') ? 'active' : '' }}">
        <a class="nav-link text-white" href="{{ route('companies.index') }}">
            <i class="fas fa-fw fa-building"></i>
            <span>Companies</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
        <a class="nav-link text-white" href="{{ route('employees.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Employees</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-left text-white w-100">
                <i class="fas fa-sign-out-alt"></i> 
                <span>Logout</span>
            </button>
        </form>
    </li>
    <hr class="sidebar-divider d-none d-md-block">
</ul> 