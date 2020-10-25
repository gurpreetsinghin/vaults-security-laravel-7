
@inject('sidebar', 'Gurpreetsinghin\VaultsSecurity\Services\SidebarService')

<aside class="main-sidebar sidebar-dark-primary elevation-4 bg-black">
<!-- <i class="fab fa-get-pocket"></i> -->
    <center><a href="{{ route('ps.admin.dashboard') }}" class="brand-link bg-black">
            <span class="brand-text font-weight-light"> <img src="{{ asset('vendor/gurpreetsinghin/vaults-security/logos/vaults-logo.png') }}" style="width: 80%;"></span>
        </a></center>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <p style="margin: auto;"><a href="{{ route('ps.admin.account') }}" class="btn btn-sm btn-secondary btn-flat"><i
                        class="fas fa-user fa-fw"></i> Account</a>
                &nbsp;&nbsp;<a href="{{ route('ps.admin.logout') }}" class="btn btn-sm btn-danger btn-flat"><i
                        class="fas fa-sign-out-alt fa-fw"></i> Logout</a></p>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-header">NAVIGATION</li>

                <li class="nav-item active">
                    <a href="{{ route('ps.admin.dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.dashboard' ? 'active' : '' }}">
                        <i class="fas fa-home"></i>&nbsp; <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.site-info') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.site-info' ? 'active' : '' }}">
                        <i class="fas fa-info-circle"></i>&nbsp; <p>Site Information</p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ Route::currentRouteName() == 'ps.admin.whitelist.ip' || Route::currentRouteName() == 'ps.admin.whitelist.file' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteName() == 'ps.admin.whitelist.ip' || Route::currentRouteName() == 'ps.admin.whitelist.file' ? 'active' : '' }}">
                        <i class="fas fa-flag"></i>&nbsp; <p>Whitelist <i class="fas fa-angle-right right"></i>
                        </p></a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item "><a href="{{ route('ps.admin.whitelist.ip') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.whitelist.ip' ? 'active' : '' }}"><i
                                    class="fas fa-user"></i>&nbsp; <p>IP Whitelist</p></a></li>
                        <li class="nav-item "><a href="{{ route('ps.admin.whitelist.file') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.whitelist.file' ? 'active' : '' }}"><i
                                    class="far fa-file-alt"></i>&nbsp; <p>File Whitelist</p></a></li>
                    </ul>
                </li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.warning-pages') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.warning-pages' ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>&nbsp; <p>Warning Pages</p>
                    </a>
                </li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.login-history') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.login-history' ? 'active' : '' }}">
                        <i class="fas fa-history"></i>&nbsp; <p>Login History</p>
                    </a>
                </li>

                <li class="nav-header">SECURITY</li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.sql-injection') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.sql-injection' ? 'active' : '' }}">
                        <i class="fas fa-code"></i>&nbsp; <p>SQL Injection {!! $sidebar->sqliSettings() !!}</p></a>
                </li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.badbots') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.badbots' ? 'active' : '' }}">
                        <i class="fas fa-user-secret"></i>&nbsp; <p>Bad Bots {!! $sidebar->badbotSettings() !!}</p></a>
                </li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.proxy') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.proxy' ? 'active' : '' }}">
                        <i class="fas fa-globe"></i>&nbsp; <p>Proxy {!! $sidebar->proxySettings() !!}</p></a>
                </li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.spam') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.spam' ? 'active' : '' }}">
                        <i class="fas fa-keyboard"></i>&nbsp; <p>Spam {!! $sidebar->spamSettings() !!}</p></a>
                </li>

                <li class="nav-item has-treeview {{ Route::currentRouteName() == 'ps.admin.logs' || Route::currentRouteName() == 'ps.admin.sqli-logs' || Route::currentRouteName() == 'ps.admin.badbot-logs' || Route::currentRouteName() == 'ps.admin.proxy-logs' || Route::currentRouteName() == 'ps.admin.spammer-logs' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteName() == 'ps.admin.logs' || Route::currentRouteName() == 'ps.admin.sqli-logs' || Route::currentRouteName() == 'ps.admin.badbot-logs' || Route::currentRouteName() == 'ps.admin.proxy-logs' || Route::currentRouteName() == 'ps.admin.spammer-logs' ? 'active' : '' }}">
                        <i class="fas fa-align-justify"></i>&nbsp; <p>Logs <i class="fas fa-angle-right right"></i>
                        </p></a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item "><a href="{{ route('ps.admin.logs') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.logs' ? 'active' : '' }}"><i
                                    class="fas fa-align-justify"></i>&nbsp; <p>All Logs <span
                                        class="badge right badge-primary">{{ $sidebar->logsCount()['all'] }}</span></p></a></li>
                        <li class="nav-item "><a href="{{ route('ps.admin.sqli-logs') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.sqli-logs' ? 'active' : '' }}"><i
                                    class="fas fa-code"></i>&nbsp; <p>SQLi Logs <span
                                        class="badge right badge-info">{{ $sidebar->logsCount()['sqli'] }}</span></p></a></li>
                        <li class="nav-item "><a href="{{ route('ps.admin.badbot-logs') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.badbot-logs' ? 'active' : '' }}"><i
                                    class="fas fa-robot"></i>&nbsp; <p>Bad Bot Logs <span
                                        class="badge right badge-danger">{{ $sidebar->logsCount()['badbot'] }}</span></p></a></li>
                        <li class="nav-item "><a href="{{ route('ps.admin.proxy-logs') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.proxy-logs' ? 'active' : '' }}"><i
                                    class="fas fa-globe"></i>&nbsp; <p>Proxy Logs <span
                                        class="badge right badge-success">{{ $sidebar->logsCount()['proxy'] }}</span></p></a></li>
                        <li class="nav-item "><a href="{{ route('ps.admin.spammer-logs') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.spammer-logs' ? 'active' : '' }}"><i
                                    class="fas fa-keyboard"></i>&nbsp; <p>Spam Logs <span
                                        class="badge right badge-warning">{{ $sidebar->logsCount()['spam'] }}</span></p></a></li>
                    </ul>
                </li>

                <li class="nav-item has-treeview {{ Route::currentRouteName() == 'ps.admin.ip-ban' || Route::currentRouteName() == 'ps.admin.country-ban' || Route::currentRouteName() == 'ps.admin.iprange-ban' || Route::currentRouteName() == 'ps.admin.other-ban' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteName() == 'ps.admin.ip-ban' || Route::currentRouteName() == 'ps.admin.country-ban' || Route::currentRouteName() == 'ps.admin.iprange-ban' || Route::currentRouteName() == 'ps.admin.other-ban' ? 'active' : '' }}">
                        <i class="fas fa-ban"></i>&nbsp; <p>Bans <i class="fas fa-angle-right right"></i>
                        </p></a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item "><a href="{{ route('ps.admin.ip-ban') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.ip-ban' ? 'active' : '' }}"><i class="fas fa-user"></i>&nbsp;
                                <p>IP Bans <span class="badge right badge-secondary">{{ $sidebar->bansCount()['ip'] }}</span></p></a></li>
                        <li class="nav-item "><a href="{{ route('ps.admin.country-ban') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.country-ban' ? 'active' : '' }}"><i
                                    class="fas fa-globe"></i>&nbsp; <p>Country Bans <span
                                        class="badge right badge-secondary">{{ $sidebar->bansCount()['country'] }}</span></p></a></li>
                        <li class="nav-item "><a href="{{ route('ps.admin.iprange-ban') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.iprange-ban' ? 'active' : '' }}"><i
                                    class="fas fa-grip-horizontal"></i>&nbsp; <p>IP Range Bans <span
                                        class="badge right badge-secondary">{{ $sidebar->bansCount()['ranges'] }}</span></p></a></li>
                        <li class="nav-item "><a href="{{ route('ps.admin.other-ban') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.other-ban' ? 'active' : '' }}"><i
                                    class="fas fa-desktop"></i>&nbsp; <p>Other Bans <span
                                        class="badge right badge-secondary">{{ $sidebar->bansCount()['other'] }}</span></p></a></li>
                    </ul>
                </li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.adblocker-detection') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.adblocker-detection' ? 'active' : '' }}">
                        <i class="fas fa-window-maximize"></i>&nbsp; <p>AdBlocker Detection {!! $sidebar->adBlockerSettings() !!}</p></a>
                </li>

                <li class="nav-item ">
                    <a href="{{ route('ps.admin.bad-words') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.bad-words' ? 'active' : '' }}">
                        <i class="fas fa-filter"></i>&nbsp; <p>Bad Words {!! $sidebar->badWords() !!}</p></a>
                </li>

                <li class="nav-header">SECURITY CHECK</li>

                <li class="nav-item {{ Route::currentRouteName() == 'ps.admin.php-functions-check' ? 'active' : '' }}">
                    <a href="{{ route('ps.admin.php-functions-check') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.php-functions-check' ? 'active' : '' }}">
                        <i class="fas fa-check"></i>&nbsp; <p>PHP Functions</p>
                    </a>
                </li>

                <li class="nav-item {{ Route::currentRouteName() == 'ps.admin.php-config-check' ? 'active' : '' }}">
                    <a href="{{ route('ps.admin.php-config-check') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.php-config-check' ? 'active' : '' }}">
                        <i class="fab fa-php"></i>&nbsp; <p>PHP Configuration</p>
                    </a>
                </li>

                <li class="nav-header">ANALYTICS &nbsp; {!! $sidebar->anaytics() !!}
                </li>

                <li class="nav-item {{ Route::currentRouteName() == 'ps.admin.live-traffic' ? 'active' : '' }}">
                    <a href="{{ route('ps.admin.live-traffic') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.live-traffic' ? 'active' : '' }}">
                        <i class="fas fa-globe"></i>&nbsp; <p>Live Traffic</p>
                    </a>
                </li>

                <li class="nav-item {{ Route::currentRouteName() == 'ps.admin.visit-analytics' ? 'active' : '' }}">
                    <a href="{{ route('ps.admin.visit-analytics') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.visit-analytics' ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>&nbsp; <p>Visit Analytics</p>
                    </a>
                </li>

                <li class="nav-header">TOOLS</li>

                <li class="nav-item {{ Route::currentRouteName() == 'ps.admin.error-monitoring' ? 'active' : '' }}">
                    <a href="{{ route('ps.admin.error-monitoring') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.error-monitoring' ? 'active' : '' }}">
                        <i class="fas fa-exclamation-circle"></i>&nbsp; <p>Error Monitoring</p>
                    </a>
                </li>

                <!-- <li class="nav-item ">
                    <a href="htaccess-editor.php" class="nav-link ">
                        <i class="fas fa-columns"></i>&nbsp; <p>.htacces Editor</p>
                    </a>
                </li> -->

                <li class="nav-item {{ Route::currentRouteName() == 'ps.admin.port-scanner' ? 'active' : '' }}">
                    <a href="{{ route('ps.admin.port-scanner') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.port-scanner' ? 'active' : '' }}">
                        <i class="fas fa-search"></i>&nbsp; <p>Port Scanner</p>
                    </a>
                </li>

                <li class="nav-item {{ Route::currentRouteName() == 'ps.admin.blacklist-checker' ? 'active' : '' }}">
                    <a href="{{ route('ps.admin.blacklist-checker') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.blacklist-checker' ? 'active' : '' }}">
                        <i class="fas fa-list"></i>&nbsp; <p>IP Blacklist Checker</p>
                    </a>
                </li>

                <li class="nav-item {{ Route::currentRouteName() == 'ps.admin.hashing' ? 'active' : '' }}">
                    <a href="{{ route('ps.admin.hashing') }}" class="nav-link {{ Route::currentRouteName() == 'ps.admin.hashing' ? 'active' : '' }}">
                        <i class="fas fa-lock"></i>&nbsp; <p>Hashing</p>
                    </a>
                </li>

            </ul>

        </nav>
    </div>

</aside>