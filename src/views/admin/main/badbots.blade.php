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
<form class="form-horizontal form-bordered" action="{{ route('ps.admin.badbots') }}" method="post">
            @csrf
<div class="content">
    <div class="container-fluid">
        <div class="row">

                <div class="col-md-8">

                    @if($bots->protection == 1)
                    <div class="card card-solid card-success">
                    @else
                    <div class="card card-solid card-danger">
                    @endif

                        <div class="card-header">
                            <h3 class="card-title">Bad Bots - Protection Module</h3>
                        </div>
                        <div class="card-body jumbotron">
                            @if($bots->protection == 1)
                            <h1 style="color: #47A447;"><i class="fas fa-check-circle"></i> Enabled</h1><p>The website is protected from <strong>Bad Bots</strong></p>
                            @else
                            <h1 style="color: #d2322d;"><i class="fas fa-times-circle"></i> Disabled</h1><p>The website is not protected from <strong>Bad Bots</strong></p>
                            @endif
                        </div>
                    </div>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-shield-alt"></i> Protection Options</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>Bad Bots</h5><hr />
                                        Detects the <b>bad bots</b> and blocks their access to the website
                                        <br /><br />
                                        
                                            <input type="checkbox" name="protection" class="psec-switch" value="1" {{ $bots->protection == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>Fake Bots</h5><hr />
                                        Detects the <b>fake bots</b> and blocks their access to the website
                                        <br /><br />
                                        
                                            <input type="checkbox" name="protection2" class="psec-switch" value="1"  {{ $bots->protection2 == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>Anonymous Bots</h5><hr />
                                        Detects the <b>anonymous bots</b> and blocks their access to the website<br />
                                        <br /><br />
                                        
                                            <input type="checkbox" name="protection3" class="psec-switch" value="1" {{ $bots->protection3 == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> What is Bad Bot</h3>
                        </div>
                        <div class="card-body">
                            <strong>Bad</strong>, <strong>Fake</strong> and <strong>Anonymous Bots</strong> are bots that consume bandwidth, slow down your server, steal your content and look for vulnerability to compromise your server.
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
                                                    <input type="checkbox" name="logging" class="psec-switch" value="1" {{ $bots->logging == 1 ? 'checked' : '' }}/><br />
                                        <span class="text-muted">Logs the detected threats</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>AutoBan</p>
                                                    <input type="checkbox" name="autoban" class="psec-switch" value="1" {{ $bots->autoban == 1 ? 'checked' : '' }}/><br />
                                        <span class="text-muted">Automatically bans the detected threats</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>Mail Notifications</p>
                                                    <input type="checkbox" name="mail" class="psec-switch" value="1" {{ $bots->mail == 1 ? 'checked' : '' }}/><br />
                                        <span class="text-muted">You will receive email notification when threat of this type is detected</span>
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
</form>
<script>
var elems = Array.prototype.slice.call(document.querySelectorAll('.psec-switch'));

elems.forEach(function(html) {
  var switchery = new Switchery(html);
});
</script>

@endsection