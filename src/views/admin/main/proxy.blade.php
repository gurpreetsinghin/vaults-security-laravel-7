@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-code"></i> Protection Module</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Protection Module</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!--Page content-->
<!--===================================================-->
<form class="form-horizontal form-bordered" action="{{ route('ps.admin.proxy') }}" method="post">
@csrf
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-8">

                @if($proxy->protection > 1 || $proxy->protection2 == 1 || $proxy->protection3 == 1)
                <div class="card card-solid card-success">
                @else
                <div class="card card-solid card-danger">
                @endif

                <div class="card-header">
                    <h3 class="card-title">Proxy - Protection Module</h3>
                </div>
                <div class="card-body jumbotron">

                    @if($proxy->protection > 1 || $proxy->protection2 == 1 || $proxy->protection3 == 1)
                    <h1 style="color: #47A447;"><i class="fas fa-check-circle"></i> Enabled</h1>
                    <p>The website is protected from <strong>Proxies</strong></p>
                    @else
                    <h1 style="color: #d2322d;"><i class="fas fa-times-circle"></i> Disabled</h1>
                    <p>The website is not protected from <strong>Proxies</strong></p>
                    @endif

                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-shield-alt"></i> Proxy Detection Methods</h3>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-body bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Detection Method #1</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="dropdown">
                                                <button class="btn btn-{{ $proxy->protection == 0 ? 'danger' : 'success' }} dropdown-toggle float-right" style="width: 100%;" type="button" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Proxy Detection API</button>
                                                <div class="dropdown-menu" style="width: 100%;">
                                                    <a class="dropdown-item {{ $proxy->protection == 0 ? 'active' : '' }}" href="{{ route('ps.admin.proxy', ['api' => 0]) }}">Disabled</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item {{ $proxy->protection == 1 ? 'active' : '' }}" href="{{ route('ps.admin.proxy', ['api' => 1]) }}">IPHub</a>
                                                    <a class="dropdown-item {{ $proxy->protection == 2 ? 'active' : '' }}" href="{{ route('ps.admin.proxy', ['api' => 2]) }}">ProxyCheck</a>
                                                    <a class="dropdown-item {{ $proxy->protection == 3 ? 'active' : '' }}" href="{{ route('ps.admin.proxy', ['api' => 3]) }}">IPHunter</a>
                                                    <a class="dropdown-item {{ $proxy->protection == 4 ? 'active' : '' }}" href="{{ route('ps.admin.proxy', ['api' => 4]) }}">BlackBox</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    Connects with Proxy Detection API and verifies if the visitor is using a Proxy, VPN
                                    or TOR
                                    <br /><br />
                                    
                                    @if ($proxy->protection > 0 && $proxy->protection != 4)
                                        
                                        @if ($data['proxy_check'] == 0)
                                            <div class="alert alert-warning" role="alert">Invalid API Key, Limit Exceeded or API is Offline</div>
                                        @endif
                                        
                                        @if($data['api_key'] == NULL OR $data['proxy_check'] == 0)
                                        <a href="{{ $data['api_key'] }}" class="btn btn-info btn-block" role="button" target="_blank"><i class="fas fa-key"></i> Get API Key</a><br />
                                        @endif

                                        <p>API Key</p>
                                        <input name="apikey" class="form-control" type="text" value="{{ $data['api_key'] }}" required>

                                    @endif
                                    
                                    @php
                                    if ($proxy->protection == 4) {
                                        if (isset($_SERVER['HTTP_USER_AGENT'])) {
                                            $useragent = $_SERVER['HTTP_USER_AGENT'];
                                        } else {
                                            $useragent = 'Mozilla/5.0';
                                        }
                                        
                                        $url = 'http://blackbox.ipinfo.app/lookup/8.8.8.8';
                                        $ch  = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                                        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
                                        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
                                        curl_setopt($ch, CURLOPT_REFERER, "https://google.com");
                                        $proxyresponse = curl_exec($ch);
                                        $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                                        curl_close($ch);
                                        
                                        if ($httpCode >= 200 && $httpCode < 300) {
                                            $proxy_check = 1;
                                            echo '<div class="alert alert-success" role="alert"><b>BlackBox Proxy Detection API</b> is active</div>';
                                        } else {
                                            echo '<div class="alert alert-warning" role="alert">Limit Exceeded or API is Offline</div>';
                                        }
                                    }
                                    @endphp
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-body bg-light">
                                    <center>
                                        <h5>Detection Method #2</h5>
                                        <hr />
                                        Checks the visitor's HTTP Headers for Proxy Elements
                                        <br /><br />

                                        <input type="checkbox" name="protection2" value="1" class="psec-switch" {{ $proxy->protection2 == 1 ? 'checked' : '' }} />
                                    </center>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-body bg-light">
                                    <center>
                                        <h5>Detection Method #3</h5>
                                        <hr />
                                        Scans the visitor's ports to detect if it is behind a Proxy or not.
                                        This detection method is mainly used to detect and block online proxy
                                        websites.<br />
                                        <strong>(False-Positives are possible)</strong>
                                        <br /><br />

                                        <input type="checkbox" name="protection3" value="1" class="psec-switch" {{ $proxy->protection3 == 1 ? 'checked' : '' }}/>
                                </div>
                                </center>
                            </div>
                        </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> What is Proxy</h3>
                </div>
                <div class="card-body">
                    <strong>Proxy</strong> or <strong>Proxy Server</strong> is basically another computer which serves
                    as a hub through which internet requests are processed. By connecting through one of these servers,
                    your computer sends your requests to the proxy server which then processes your request and returns
                    what you were wanting.
                </div>
            </div>
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Module Settings</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                            <li class="list-group-item">
                                <p>Logging</p>
                                <input type="checkbox" name="logging" value="1" class="psec-switch" {{ $proxy->logging == 1 ? 'checked' : '' }}/><br />
                                <span class="text-muted">Logs the detected threats</span>
                            </li>
                            <li class="list-group-item">
                                <p>AutoBan</p>
                                <input type="checkbox" name="autoban" value="1" class="psec-switch" {{ $proxy->autoban == 1 ? 'checked' : '' }}/><br />
                                <span class="text-muted">Automatically bans the detected threats</span>
                            </li>
                            <li class="list-group-item">
                                <p>Mail Notifications</p>
                                <input type="checkbox" name="mail" value="1" class="psec-switch" {{ $proxy->mail == 1 ? 'checked' : '' }}/><br />
                                <span class="text-muted">Receive email notifications when threat of this type is
                                    detected</span>
                            </li>
                            <li class="list-group-item">
                                <p>Redirect URL</p>
                                <input name="redirect" class="form-control" type="text" value="{{ $proxy->redirect }}" required>
                            </li>
                    </ul>
                </div>
            </div>
            <div class="card-footer">
                        <button class="btn btn-flat btn-block btn-primary mar-top" type="submit"><i class="fas fa-save"></i> Save</button>
                    </div>
        </div>

    </div>

</div>
</div>
<!--===================================================-->
<!--End page content-->

</div>
</form>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
<script>
var elems = Array.prototype.slice.call(document.querySelectorAll('.psec-switch'));

elems.forEach(function(html) {
    var switchery = new Switchery(html);
});
</script>

@endsection