@extends('project-security::layouts.admin')
@section('content')

@inject('common', 'Gurpreetsinghin\VaultsSecurity\Services\CommonService')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-home"></i> Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!--Page content-->
<!--===================================================-->
<div class="content">
    <div class="container-fluid">

        <h4 class="card-title">Today's Stats</h4><br />
        <div class="row">

            <div class="col-sm-6 col-lg-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $data['count1'] }}</h3>
                        <p>SQLi Attacks</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <a href="sqli-logs.php" class="small-box-footer">View Logs <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $data['count2'] }}</h3>
                        <p>Bad Bots</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <a href="badbot-logs.php" class="small-box-footer">View Logs <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $data['count3'] }}</h3>
                        <p>Proxies</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <a href="proxy-logs.php" class="small-box-footer">View Logs <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $data['count4'] }}</h3>
                        <p>Spammers</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <a href="spammer-logs.php" class="small-box-footer">View Logs <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <br />
        <h4 class="card-title">Overall Statistics</h4><br />

        <div class="row">
            <div class="col-lg-7">
                <div id="panel-network" class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar"></i> Threat Statistics</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="log-stats"></canvas>
                    </div>
                </div>

            </div>
            <div class="col-lg-5">
                <div class="row">
                    <div class="col-sm-6 col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-lg">SQL Injections</p>
                                <i class="fas fa-code fa-2x"></i>
                                <hr>
                                <p class="h3 text-thin">{{ $data['countm1'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <div class="card card-danger card-outline">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-lg">Bad Bots</p>
                                <i class="fas fa-robot fa-2x"></i>
                                <hr>
                                <p class="h3 text-thin">{{ $data['countm2'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-lg-6">
                        <div class="card card-success card-outline">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-lg">Proxies</p>
                                <i class="fas fa-globe fa-2x"></i>
                                <hr>
                                <p class="h3 text-thin">{{ $data['countm3'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <div class="card card-warning card-outline">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-lg">Spammers</p>
                                <i class="fas fa-keyboard fa-2x"></i>
                                <hr>
                                <p class="h3 text-thin">{{ $data['countm4'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-stream"></i> Modules Status</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-body bg-light">
                            <center>
                                <h5><i class="fas fa-shield-alt"></i> &nbsp;Protection Modules</h5>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-code"></i> SQLi</strong><br />Protection
                                <hr />

                                @if ($setting['sqli']->protection == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-robot"></i> Bad Bots</strong><br />Protection
                                <hr />

                                @if ($setting['badbot']->protection == 1 || $setting['badbot']->protection2 == 1 ||
                                $setting['badbot']->protection3 == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-globe"></i> Proxy</strong><br />Protection<br />
                                <hr />

                                @if ($setting['proxy']->protection == 1 || $setting['proxy']->protection2 == 1 ||
                                $setting['proxy']->protection3 == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-keyboard"></i> Spam</strong><br />Protection<br />
                                <hr />

                                @if ($setting['spam']->protection == 1 )
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-body bg-light">
                            <center>
                                <h5><i class="fas fa-list-ul"></i> &nbsp;Logging Settings</h5>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-code"></i> SQLi</strong><br />Logging
                                <hr />

                                @if ($setting['sqli']->logging == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-robot"></i> Bad Bots</strong><br />Logging
                                <hr />

                                @if ($setting['badbot']->logging == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-globe"></i> Proxy</strong><br />Logging <br />
                                <hr />

                                @if ($setting['proxy']->logging == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-keyboard"></i> Spam</strong><br />Logging<br />
                                <hr />

                                @if ($setting['spam']->logging == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-body bg-light">
                            <center>
                                <h5><i class="fas fa-ban"></i> &nbsp;AutoBan Settings</h5>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-code"></i> SQLi</strong><br />AutoBan
                                <hr />

                                @if ($setting['sqli']->autoban == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-robot"></i> Bad Bots</strong><br />AutoBan
                                <hr />

                                @if ($setting['badbot']->autoban == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-globe"></i> Proxy</strong><br />AutoBan<br />
                                <hr />

                                @if ($setting['proxy']->autoban == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-body bg-light">
                            <center>
                                <strong><i class="fas fa-keyboard"></i> Spam</strong><br />AutoBan<br />
                                <hr />

                                @if ($setting['spam']->autoban == 1)
                                <h4><span class="badge badge-success"><i class="fas fa-check"></i> ON</span></h4>
                                @else
                                <h4><span class="badge badge-danger"><i class="fas fa-times"></i> OFF</span></h4>
                                @endif
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-dark"><i class="fas fa-globe"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">GeoIP API Status</span>
                        <span class="info-box-number">
                            @if($data['gstatus'] == 'online')
                            <font color="green">Online</font>
                            @else
                            <font color="red">Offline</font>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-dark"><i class="fas fa-cloud"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Proxy Detection API Status</span>
                        <span class="info-box-number">
                            @if ($data['proxy_check'] == 1)
                            <font color="green">Online</font>
                            @elseif ($data['proxy_check'] == 0)
                            <font color="red">Offline</font>
                            @else
                            <font color="red">Disabled</font>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-dark">
                    <div class="card-header with-border">
                        <h3 class="card-title">Recent Logs</h3>
                    </div>
                    <div class="card-body">
                        @if($data['logs']->count() > 0)
                        @foreach($data['logs'] as $log)
                        <h4
                            style="background-color:#f7f7f7; font-size: 16px; text-align: center; padding: 7px 10px; margin-top: 0;">
                            <i class="fas fa-user pull-left"></i> {{ $log->ip }}
                        </h4>

                        <div class="media">
                            <div class="mr-3">
                                <i class="fas fa-user-secret fa-3x"></i>
                            </div>
                            <div class="media-body">
                                <p>
                                    <i class="fas fa-file-alt"></i> Threat Type:
                                    @if ($log->type == 'SQLi')
                                    <button class="btn btn-sm btn-primary btn-flat"><i class="fas fa-code"></i> <b>{{ $log->type }}</b></button>
                                    @elseif ($log->type == 'Bad Bot' || $log->type == 'Fake Bot' || $log->type ==
                                    'Missing User-Agent header' || $log->type == 'Missing header Accept' || $log->type
                                    == 'Invalid IP Address header')
                                    <button class="btn btn-sm btn-danger btn-flat"><i class="fas fa-robot"></i> <b>{{ $log->type }}</b></button>
                                    @elseif ($log->type == 'Proxy')
                                    <button class="btn btn-sm btn-success btn-flat"><i class="fas fa-globe"></i> <b>{{ $log->type }}</b></button>
                                    @elseif ($log->type == 'Spammer')
                                    <button class="btn btn-sm btn-warning btn-flat"><i class="fas fa-keyboard"></i> <b>{{ $log->type }}</b></button>
                                    @else
                                    <button class="btn btn-sm btn-success btn-flat"><i class="fas fa-user-secret"></i>
                                        <b>Other</b></button>
                                    @endif
                                </p>
                                <p><i class="fas fa-calendar"></i> {{ $log->date.' at '.$log->time }}</p>
                            </div>
                            <p class="ml-3">

                                <a href="{{ route('ps.admin.view', $log->id) }}"
                                    class="btn btn-sm btn-flat btn-block btn-primary"><i class="fas fa-tasks"></i>
                                    Details</a>
                                @if($common->isBanned($log->ip))
                                <a href="{{ route('ps.admin.unban-ip', $common->getBannedId($log->ip)) }}"
                                    class="btn btn-sm btn-flat btn-block btn-success"><i class="fas fa-trash"></i>
                                    Unban</a>
                                @else
                                <a href="{{ route('ps.admin.ban-ip', $log->ip.'&&&'.$log->type) }}"
                                    class="btn btn-sm btn-flat btn-block btn-warning"><i class="fas fa-ban"></i> Ban</a>
                                @endif
                                <a href="{{ route('ps.admin.logs.delete', $log->id) }}"
                                    class="btn btn-sm btn-flat btn-block btn-danger"><i class="fas fa-times"></i>
                                    Delete</a>

                            </p>
                        </div>
                        
                        @endforeach
                        <hr>
                        <center><a href="{{ route('ps.admin.logs') }}" class="btn btn-flat btn-block btn-primary"><i
                                    class="fas fa-search"></i> View All</a></center>
                        @else
                        <div class="alert alert-info">
                            <p>There are no recent <b>Logs</b></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-dark">
                    <div class="card-header with-border">
                        <h3 class="card-title">Recent IP Bans</h3>
                    </div>
                    <div class="card-body">
                        @if($data['bans']->count() > 0)
                        @foreach($data['bans'] as $ban)
                        <h4 style="background-color:#f7f7f7; font-size: 16px; text-align: center; padding: 7px 10px; margin-top: 0;">
                            <i class="fas fa-user pull-left"></i> {{ $ban->ip }}</h4>

                        <div class="media">
                            <div class="mr-3">
                                <i class="fas fa-ban fa-3x"></i>
                            </div>
                            <div class="media-body">
                                <p><i class="fas fa-file-alt"></i> {{ $ban->reason }}</p>
                                <p><i class="fas fa-calendar"></i> {{ $ban->date }} at {{ $ban->time }}</p>

                                <p style="margin-bottom: 0">
                                    <button class="btn btn-sm btn-flat btn-danger"><i class="fas fa-magic"></i> Autobanned: <b>{{ $ban->autoban == 1 ? 'Yes' : 'No' }}</b></button>
                                </p>
                            </div>
                            <p class="ml-3">
                                <a href="{{ route('ps.admin.ip-ban.edit', $ban->id) }}" class="btn btn-sm btn-flat btn-block btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                <a href="{{ route('ps.admin.ip-ban.delete', $ban->id) }}" class="btn btn-sm btn-flat btn-block btn-success"><i class="fas fa-trash"></i> Unban</a>
                            </p>
                        </div>
                        @endforeach
                        <hr>
                        <center><a href="{{ route('ps.admin.ip-ban') }}" class="btn btn-flat btn-block btn-primary"><i class="fas fa-search"></i> View All</a></center>
                        @else
                        <div class="alert alert-info">
                            <p>There are no recent <b>IP Bans</b></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-dark">
                    <div class="card-header with-border">
                        <h3 class="card-title">Statistics</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr class="active">
                                    <th><i class="fas fa-list"></i> Threat Logs</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total</td>
                                    <td>{{ $data['all_logs']['total'] }}</td>
                                </tr>
                                <tr>
                                    <td>Today</td>
                                    <td>{{ $data['all_logs']['today'] }}</td>
                                </tr>
                                <tr>
                                    <td>This month</td>
                                    <td>{{ $data['all_logs']['this_month'] }}</td>
                                </tr>
                                <tr>
                                    <td>This year</td>
                                    <td>{{ $data['all_logs']['this_year'] }}</td>
                                </tr>
                            </tbody>
                            <thead class="thead-light">
                                <tr class="active">
                                    <th><i class="fas fa-ban"></i> IP Bans</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total</td>
                                    <td>{{ $data['all_bans']['total'] }}</td>
                                </tr>
                                <tr>
                                    <td>Today</td>
                                    <td>{{ $data['all_bans']['today'] }}</td>
                                </tr>
                                <tr>
                                    <td>This month</td>
                                    <td>{{ $data['all_bans']['this_month'] }}</td>
                                </tr>
                                <tr>
                                    <td>This year</td>
                                    <td>{{ $data['all_bans']['this_year'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">Threats by Country</h3>
            </div>
            <div class="card-body">
                <div class="col-md-12">

                    <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th><i class="fas fa-globe"></i> Country</th>
                                <th><i class="fas fa-bug"></i> Threats</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['countries'] as $country)
                        @php $result = DB::table($table)->select('country_code')->where('country', 'LIKE',
                        '%'.$country.'%')->first(); @endphp
                        @if(!empty($result))
                        <tr>
                            <td><img src="{{ asset('vendor/gurpreetsinghin/vaults-security/admin/plugins/flags/blank.png') }}"
                                    class="flag flag-{{ strtolower($result->country_code) }}"
                                    alt="{{ $country }}" /> {{ $country }}</td>
                            <td>{{ $result->count() }}</td>
                        </tr>
                        @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--===================================================-->
<!--End page content-->


<script>
var barChartData = {
    labels: [
        <?php
$i = 1;
while ($i <= 12) {
    $date = date('F', mktime(0, 0, 0, $i, 1));
    echo "'$date'";
    if ($i != 12) {
        echo ',';
    }
    $i++;
}
?>
    ],
    datasets: [{
        label: 'SQLi',
        backgroundColor: '#007bff',
        stack: '1',
        data: [
            <?php
$i     = 1;
while ($i <= 12) {
    $date   = date('F Y', mktime(0, 0, 0, $i, 1));
    $tcount = DB::table($table)->where('date', 'LIKE', '%'.$date)->where('type', 'SQLi')->count();
    echo "'$tcount'";
    if ($i != 12) {
        echo ',';
    }
    $i++;
}
?>
        ]
    }, {
        label: 'Bad Bot',
        backgroundColor: '#dc3545',
        stack: '2',
        data: [
            <?php
$i     = 1;
while ($i <= 12) {
    $date   = date('F Y', mktime(0, 0, 0, $i, 1));
    $tcount = DB::table($table)->where('date', 'LIKE', '%'.$date)->where(function($query){
        $query->where('type', 'Bad Bot');
        $query->orWhere('type', 'Fake Bot');
        $query->orWhere('type', 'Missing User-Agent header');
        $query->orWhere('type', 'Missing header Accept');
        $query->orWhere('type', 'Invalid IP Address header');
    })->count();
    echo "'$tcount'";
    if ($i != 12) {
        echo ',';
    }
    $i++;
}
?>
        ]
    }, {
        label: 'Proxies',
        backgroundColor: '#28a745',
        stack: '3',
        data: [
            <?php
$i     = 1;
while ($i <= 12) {
    $date   = date('F Y', mktime(0, 0, 0, $i, 1));
    $tcount = DB::table($table)->where('date', 'LIKE', '%'.$date)->where('type', 'Proxy')->count();
    echo "'$tcount'";
    if ($i != 12) {
        echo ',';
    }
    $i++;
}
?>
        ]
    }, {
        label: 'Spammers',
        backgroundColor: '#ffc107',
        stack: '4',
        data: [
            <?php
$i     = 1;
while ($i <= 12) {
    $date   = date('F Y', mktime(0, 0, 0, $i, 1));
    $tcount = DB::table($table)->where('date', 'LIKE', '%'.$date)->where('type', 'Spammer')->count();
    echo "'$tcount'";
    if ($i != 12) {
        echo ',';
    }
    $i++;
}
?>
        ]
    }]

};
window.onload = function() {
    var ctx = document.getElementById('log-stats').getContext('2d');
    window.myBar = new Chart(ctx, {
        type: 'bar',
        data: barChartData,
        options: {
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }
    });
};
</script>


@endsection