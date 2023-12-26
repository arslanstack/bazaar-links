<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javscript:void();"><i class="fa fa-bars"></i> </a>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li>
            <span class="m-r-sm text-muted welcome-message">Welcome to {{ get_section_content('project', 'site_title') }}</span>
        </li>
        <li>
            <a href="{{ url('admin/change_password') }}">
                <i class="fa fa-key"></i> Change Password
            </a>
        </li>
        <li>
            <a href="{{ url('admin/logout') }}">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </li>
    </ul>
</nav>