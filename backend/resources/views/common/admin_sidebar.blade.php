<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle" style="width: 50px;" src="{{ asset('admin_assets/img/profile_small.jpg') }}"/>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">Welcome {{ ucwords(Auth::guard('admin')->user()->username) }}</span>
                        <span class="text-muted text-xs block">
                            {{ get_section_content('project', 'site_title') }}
                        </span>
                    </a>
                </div>
                <div class="logo-element">
                    {{ ucwords(Auth::guard('admin')->user()->username) }}
                    <span class="text-muted text-xs block">
                        {{ get_section_content('project', 'short_site_title') }}
                    </span>
                </div>
            </li>
            <li class="{{ Request::is('admin') ? 'active' : '' }} {{ Request::is('admin/admin') ? 'active' : '' }} {{ Request::is('admin/change_password') ? 'active' : '' }}">
                <a href="{{ url('admin') }}"><i class="fa-solid fa-gauge-high"></i> <span class="nav-label">Dashboard</span></a>
            </li>
            <li class="{{ Request::is('admin/users') ? 'active' : '' }} {{ Request::is('admin/users/detail*') ? 'active' : '' }}">
                <a href="{{ url('admin/users') }}"><i class="fa-solid fa-users"></i> <span class="nav-label">Users</span></a>
            </li>
            <li class="{{ Request::is('admin/categories') ? 'active' : '' }} {{ Request::is('admin/categories/*') ? 'active' : '' }}">
                <a href="{{ url('admin/categories') }}"><i class="fa-solid fa-table"></i> <span class="nav-label">Categories</span></a>
            </li>
            <li class="{{ Request::is('admin/product-posts') ? 'active' : '' }} {{ Request::is('admin/product-posts/*') ? 'active' : '' }}">
                <a href="{{ url('admin/product-posts') }}"><i class="fa-solid fa-image"></i> <span class="nav-label">Product Posts</span></a>
            </li>
            <li class="{{ Request::is('admin/product-requests') ? 'active' : '' }} {{ Request::is('admin/product-requests/*') ? 'active' : '' }}">
                <a href="{{ url('admin/product-requests') }}"><i class="fa-solid fa-person-circle-question"></i> <span class="nav-label">Product Requests</span></a>
            </li>
        </ul>
    </div>
</nav>