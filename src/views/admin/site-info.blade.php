@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-info-circle"></i> Site Information</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Site Information</li>
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
            <div class="col-md-6">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">{{ $data['site'] }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr style="background-color: #F3F4F5;">
                                        <th>Site Stats &amp; Information</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Response Time</td>
                                        <td>
                                            <h5><span class="badge badge-success">0.001868 sec</span></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PHP Configuration File (php.ini)</td>
                                        <td>
                                            <h5><span class="badge badge-warning">{{ $data['iniflp'] }}</span></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PHP Error Log</td>
                                        <td>
                                            <h5><span class="badge badge-warning">{{ $data['errorlog_path'] }}</span></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Zend Version</td>
                                        <td>
                                            <h5><span class="badge badge-danger">{{ $data['zend_version'] }}</span></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Default Timezone</td>
                                        <td>
                                            <h5><span class="badge badge-primary">{{ date_default_timezone_get() }}</span></h5>
                                        </td>
                                    </tr>
                                </tbody>

                                <!-- <thead>
                                    <tr style="background-color: #F3F4F5;">
                                        <th>Meta Tags</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Title</td>
                                        <td>Sample – Just another WordPress site</td>
                                    </tr>
                                    <tr>
                                        <td>Description</td>
                                        <td>
                                            <h5><span class="badge badge-secondary">No Description</span></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Keywords</td>
                                        <td>
                                            <h5><span class="badge badge-secondary">No Keywords</span></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Author</td>
                                        <td>
                                            <h5><span class="badge badge-secondary">No Author</span></h5>
                                        </td>
                                    </tr>
                                </tbody> -->
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $data['files'] }}</h3>
                                <p>Files</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data['folders'] }}</h3>
                                <p>Folders</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-folder"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data['images'] }}</h3>
                                <p>Images</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-image"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $data['php'] }}</h3>
                                <p>PHP Files</p>
                            </div>
                            <div class="icon">
                                <i class="fab fa-php"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data['html'] }}</h3>
                                <p>HTML Files</p>
                            </div>
                            <div class="icon">
                                <i class="fas  fa-file-code"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data['css'] }}</h3>
                                <p>CSS Files</p>
                            </div>
                            <div class="icon">
                                <i class="fab fa-css3-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $data['js'] }}</h3>
                                <p>JSS Files</p>
                            </div>
                            <div class="icon">
                                <i class="fab fa-js"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-hdd"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">HDD Total Space</span>
                                <span class="info-box-number">{{ $data['disk_size']['total'] }}</span>

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        style="width: {{ $data['disk_size']['used_percent'] }}%"></div>
                                </div>
                                <span class="progress-description">
                                    Used Space: <span class="text-semibold">{{ $data['disk_size']['used'] }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-hdd"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">HDD FREE SPACE</span>
                                <span class="info-box-number">{{ $data['disk_size']['free'] }}</span>

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        style="width: {{ $data['disk_size']['free_percent'] }}%"></div>
                                </div>
                                <span class="progress-description">Free <span class="text-semibold">{{ $data['disk_size']['free_percent'] }}%</span> of
                                    <span class="text-semibold">{{ $data['disk_size']['total'] }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <p><i class="fas fa-hdd"></i> Free HDD Space</p>
                <div class="progress progress-xl light">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar"
                        aria-valuenow="{{ $data['disk_size']['free_percent'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $data['disk_size']['free_percent'] }}%;">
                        <strong>{{ $data['disk_size']['free_percent'] }}%</strong>
                    </div>
                </div>

                <p><i class="fas fa-hdd"></i> Used HDD Space</p>
                <div class="progress progress-xl light">
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar"
                        aria-valuenow="{{ $data['disk_size']['used_percent'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $data['disk_size']['used_percent'] }}%;">
                        <strong>{{ $data['disk_size']['used_percent'] }}%</strong>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Loaded PHP Extensions - <span class="badge badge-primary">{{ $data['extensions']['count'] }}</span></h3>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light">
                            <ul>
                                @foreach($data['extensions']['list'] as $ext)
                                <li>{{ $ext }}</li>
                                @endforeach
                            </ul>
                        </pre>
                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <h3 class="mt-none">Host Information</h3>
                <p>Information about the Web Host, IP Address, Name Servers &amp; More.</p><br>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">Domain IP</p>
                                <i class="fas fa-user fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">{{ $data['host']['ip'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">Country</p>
                                <i class="fas fa-globe fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">{{ $data['host']['country'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">Server Software</p>
                                <i class="fas fa-database fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">
                                    {{ $data['soft'] }} </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">ISP</p>
                                <i class="fas fa-tasks fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">{{ $data['host']['isp'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">Server OS</p>
                                <i class="fas fa-desktop fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">
                                {{ $data['os'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">PHP Version</p>
                                <i class="fas fa-file-code fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">{{ phpversion() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">MySQL Version</p>
                                <i class="fas fa-list-alt fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">8.0.21</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">Server Port</p>
                                <i class="fas fa-plug fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">{{ $_SERVER['SERVER_PORT'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">OpenSSL Version</p>
                                <i class="fas fa-lock fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">
                                @php
                                    if (!extension_loaded('openssl')) {
                                    echo '<font style="color: red;">Deactivated</font>';
                                    } else {
                                    echo str_replace("OpenSSL", "", OPENSSL_VERSION_TEXT);
                                    }
                                @endphp    
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">cURL Extension</p>
                                <i class="fas fa-link fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">
                                @php
                                    if (function_exists('curl_version')) {
                                    $values = curl_version();
                                    echo $values["version"];
                                    } else {
                                    echo '<font style="color: red;">Disabled</font>';
                                    }
                                @endphp
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">HTTP Protocol</p>
                                <i class="fas fa-hdd fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">{{ $_SERVER['SERVER_PROTOCOL'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="text-uppercase mar-btm text-sm" style="font-size: 20px">Gateway Interface</p>
                                <i class="fas fa-sitemap fa-3x"></i>
                                <hr>
                                <p class="h4 text-thin">{{ @$_SERVER['GATEWAY_INTERFACE'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->

@endsection