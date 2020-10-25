<nav class="main-header navbar navbar-expand navbar-dark bg-black border-bottom">

    <ul class="nav navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
    </ul>

    <form class="form-inline ml-3" action="{{ route('ps.admin.ip-lookup') }}" method="get">
        <div class="input-group input-group-sm">
            <input type="text" name="ip" value="{{ request()->get('ip') }}" class="form-control form-control-navbar" placeholder="IP Lookup" required>
            <div class="input-group-append">
                <button type="submit" class="btn btn-navbar"><i class="fa fa-search"></i></button>
                </button>
            </div>
        </div>
    </form>

    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item d-none d-md-block">
            <a href="{{ url('/') }}" class="nav-link" target="_blank" title="View Site">
                <i class="fas fa-desktop"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ps.admin.settings') }}" class="nav-link" title="Settings"><i class="fas fa-cogs"></i></a>
        </li>

    </ul>
</nav>