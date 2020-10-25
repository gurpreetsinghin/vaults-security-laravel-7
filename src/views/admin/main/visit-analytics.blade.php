@extends('project-security::layouts.admin')
@section('content')


<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-chart-line"></i> Visit Analytics</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Visit Analytics</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!--Page content-->
<!--===================================================-->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Visit Analytics</h3>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Today's Stats</h4><br />
                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $data['tscount1'] }}</h3>
                                        <p>Online Visitors</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $data['tscount2'] }}</h3>
                                        <p>Unique Visits</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ $data['tscount3'] }}</h3>
                                        <p>Total Visits</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $data['tscount4'] }}</h3>
                                        <p>Bot Visits</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fab fa-android"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <h4 class="card-title">This Month's Stats</h4><br />

                        <div class="row">

                            <div class="col-sm-6 col-lg-4">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $data['mscount1'] }}</h3>
                                        <p>Unique Visits</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ $data['mscount2'] }}</h3>
                                        <p>Total Visits</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $data['mscount3'] }}</h3>
                                        <p>Bot Visits</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fab fa-android"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br />
                        <h4 class="card-title">Visits This Month</h4><br />

                        <canvas id="visits-chart"></canvas>

                        <br />
                        <h4 class="card-title">Overall Statistics</h4><br />
                        <div class="row">
                            <div class="col-md-6">
                                <center>
                                    <h5><i class="fas fa-globe"></i> Browser Statistics</h5>
                                </center>
                                <div id="canvas-holder" style="width:100%">
                                    <canvas id="browser-graph"></canvas>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <center>
                                    <h5><i class="fas fa-desktop"></i> Operating System Statistics</h5>
                                </center>
                                <div id="canvas-holder" style="width:100%">
                                    <canvas id="os-graph"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <br />
                                <center>
                                    <h5><i class="fas fa-mobile-alt"></i> Device Statistics</h5>
                                </center>
                                <div id="canvas-holder" style="width:100%">
                                    <canvas id="device-graph"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <hr />
                            <h5>Visits by Country</h5><br />

                            <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-globe"></i> Country</th>
                                        <th><i class="fas fa-users"></i> Visitors</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($countries as $country)
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
    </div>
</div>

<script>
var config = {
    type: 'pie',
    data: {
        datasets: [{
            data: [
                <?php echo $data['bcount1']; ?>,
                <?php echo $data['bcount2']; ?>,
                <?php echo $data['bcount3']; ?>,
                <?php echo $data['bcount4']; ?>,
                <?php echo $data['bcount5']; ?>,
                <?php echo $data['bcount6']; ?>,
                <?php echo $data['bcount7']; ?>,
            ],
            backgroundColor: [
                '#32CD32',
                '#FFD700',
                '#FF0000',
                '#00BFFF',
                '#1E90FF',
                '#B0C4DE',
                '#000000',
            ]
        }],
        labels: [
            'Google Chrome',
            'Firefox',
            'Opera',
            'Edge',
            'Internet Explorer',
            'Safari',
            'Other'
        ]
    },
    options: {
        responsive: true
    }
};


var config2 = {
    type: 'pie',
    data: {
        datasets: [{
            data: [
                <?php echo $data['ocount1']; ?>,
                <?php echo $data['ocount2']; ?>,
                <?php echo $data['ocount3']; ?>,
                <?php echo $data['ocount4']; ?>,
                <?php echo $data['ocount5']; ?>,
                <?php echo $data['ocount6']; ?>,
            ],
            backgroundColor: [
                '#1E90FF',
                '#FFD700',
                '#7CFC00',
                '#D3D3D3',
                '#B0C4DE',
                '#000000',
            ]
        }],
        labels: [
            'Windows',
            'Linux',
            'Android',
            'iOS',
            'Mac OS X',
            'Other'
        ]
    },
    options: {
        responsive: true
    }
};

var config3 = {
    type: 'pie',
    data: {
        datasets: [{
            data: [
                <?php echo $data['pcount2']; ?>,
                <?php echo $data['pcount3']; ?>,
                <?php echo $data['pcount1']; ?>,
            ],
            backgroundColor: [
                '#00BFFF',
                '#FFD700',
                '#FF0000',
            ]
        }],
        labels: [
            'Mobile',
            'Tablet',
            'Computer'
        ]
    },
    options: {
        responsive: true
    }
};

var config4 = {
    type: 'line',
    data: {
        labels: [
            <?php
$i    = 1;
@$days = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));
while ($i <= $days) {
    echo "'$i'";
    
    if ($i != $days) {
        echo ',';
    }
    
    $i++;
}
?>
        ],
        datasets: [{
            label: 'Total Visits',
            backgroundColor: '#1E90FF',
            borderColor: '#1E90FF',
            data: [
                <?php
$i    = 1;
$days = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));
while ($i <= $days) {
    @$mdatef = sprintf("%02d", $i) . ' ' . date("F Y");
    $mquery1 = DB::table($table)->where(['date' => $mdatef])->count();
    echo "'$mquery1'";
    
    if ($i != $days) {
        echo ',';
    }
    
    $i++;
}
?>
            ],
            fill: false,
        }, {
            label: 'Unique Visits',
            fill: false,
            backgroundColor: '#3CB371',
            borderColor: '#3CB371',
            data: [
                <?php
$i    = 1;
$days = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));
while ($i <= $days) {
    @$mdatef = sprintf("%02d", $i) . ' ' . date("F Y");
    $mquery2 = DB::table($table)->where(['date' => $mdatef, 'uniquev' => 1])->count();
    echo "'$mquery2'";
    
    if ($i != $days) {
        echo ',';
    }
    
    $i++;
}
?>
            ],
        }]
    },
    options: {
        responsive: true,
        tooltips: {
            mode: 'index',
            intersect: false,
        },
        hover: {
            mode: 'nearest',
            intersect: true
        },
        scales: {
            xAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: '<?php
echo date("F Y");
?> '
                }
            }],
            yAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Visits'
                }
            }]
        }
    }
};

window.onload = function() {
    var ctx = document.getElementById('browser-graph').getContext('2d');
    window.browsergraph = new Chart(ctx, config);
    var ctx2 = document.getElementById('os-graph').getContext('2d');
    window.osgraph = new Chart(ctx2, config2);
    var ctx3 = document.getElementById('device-graph').getContext('2d');
    window.devicegraph = new Chart(ctx3, config3);
    var ctx4 = document.getElementById('visits-chart').getContext('2d');
    window.visitschart = new Chart(ctx4, config4);
};

</script>

@endsection