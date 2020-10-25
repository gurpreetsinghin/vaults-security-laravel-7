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
<form class="form-horizontal form-bordered" action="{{ route('ps.admin.sql-injection') }}" method="post">
            @csrf
<div class="content">
    <div class="container-fluid">
        <div class="row">

                <div class="col-md-8">

                    @if($sql->protection == 1)
                    <div class="card card-solid card-success">
                    @else
                    <div class="card card-solid card-danger">
                    @endif

                        <div class="card-header">
                            <h3 class="card-title">SQL Injection - Protection Module</h3>
                        </div>
                        <div class="card-body jumbotron">
                            @if($sql->protection == 1)
                            <h1 style="color: #47A447;"><i class="fas fa-check-circle"></i> Enabled</h1>
                            <p>The website is protected from <strong>SQL Injection Attacks (SQLi)</strong></p>
                            @else
                            <h1 style="color: #d2322d;"><i class="fas fa-times-circle"></i> Disabled</h1>
                            <p>The website is not protected from <strong>SQL Injection Attacks (SQLi)</strong></p>
                            @endif
                        </div>
                    </div>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-shield-alt"></i> Additional Protection Options</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>XSS Protection</h5><hr />
                                        Sanitizes infected requests
                                        <br /><br /><br />
                                        
                                            <input type="checkbox" name="protection2" class="psec-switch" value="1" {{ $sql->protection2 == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>Clickjacking Protection</h5><hr />
                                        Detecting and blocking clickjacking attempts
                                        <br /><br />
                                        
                                            <input type="checkbox" name="protection3" class="psec-switch" value="1"  {{ $sql->protection3 == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>Hide PHP Information</h5><hr />
                                        Hides the PHP version to remote requests
                                        <br /><br />
                                        
                                            <input type="checkbox" name="protection6" class="psec-switch" value="1" {{ $sql->protection6 == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>MIME Mismatch Attacks Protection</h5><hr />
                                        Prevents attacks based on MIME-type mismatch
                                        <br /><br />
                                        
                                            <input type="checkbox" name="protection4" class="psec-switch" value="1" {{ $sql->protection4 == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>Secure Connection</h5><hr />
                                        Forces the website to use secure connection (HTTPS)
                                        <br /><br /><br />
                                        
                                            <input type="checkbox" name="protection5" class="psec-switch" value="1" {{ $sql->protection5 == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>Data Filtering</h5><hr />
                                        Basic Sanitization of all fields, inputs, forms and requests. <i>Lower sensativity, faster performance.</i>
                                        <br /><br />
                                        
                                            <input type="checkbox" name="protection7" class="psec-switch" value="1" {{ $sql->protection7 == 1 ? 'checked' : '' }}/>
                                        </center>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-body bg-light">
                                        <center>
                                        <h5>Requests Sanitization</h5><hr />
                                        Advanced Sanitization of all fields, inputs, forms and requests. <i>Higher sensativity, slower performance.</i>
                                        <br /><br />
                                        
                                            <input type="checkbox" name="protection8" class="psec-switch" value="1" {{ $sql->protection8 == 1 ? 'checked' : '' }}/>
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
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> What is SQL Injection</h3>
                        </div>
                        <div class="card-body">
                            <strong>SQL Injection</strong> is a technique where malicious users can inject SQL commands into an SQL statement, via web page input. Injected SQL commands can alter SQL statement and compromise the security of a web application.
                        </div>
                    </div>
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cogs"></i> Module Settings</h3>
                        </div>
                        
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                            <p>Protection</p>
                                                    <input type="checkbox" name="protection" class="psec-switch" value="1" {{ $sql->protection == 1 ? 'checked' : '' }}/><br />
                                        <span class="text-muted">If this protection module is enabled all threats of this type will be blocked</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>Logging</p>
                                                    <input type="checkbox" name="logging" class="psec-switch" value="1" {{ $sql->logging == 1 ? 'checked' : '' }}/><br />
                                        <span class="text-muted">Logs the detected threats</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>AutoBan</p>
                                                    <input type="checkbox" name="autoban" class="psec-switch" value="1" {{ $sql->autoban == 1 ? 'checked' : '' }}/><br />
                                        <span class="text-muted">Automatically bans the detected threats</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>Mail Notifications</p>
                                                    <input type="checkbox" name="mail" class="psec-switch" value="1" {{ $sql->mail == 1 ? 'checked' : '' }}/><br />
                                        <span class="text-muted">You will receive email notification when threat of this type is detected</span>
                                    </li>
                                    <li class="list-group-item">
                                        <p>Redirect URL</p>
                                        <input name="redirect" class="form-control" type="text" value="{{ $sql->redirect }}" required>
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