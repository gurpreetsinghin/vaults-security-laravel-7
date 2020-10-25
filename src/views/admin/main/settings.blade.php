@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-cogs"></i> Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Settings</li>
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
                <form class="form-horizontal" action="{{ route('ps.admin.settings') }}" method="post">
                    @csrf
                    <div class="col-md-12 card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cog"></i> Settings</h3>
                        </div>
                        <div class="card-body mx-auto">
                            <div class="form-group row">
                                <label class="control-label" for="inputDefault">E-Mail Address:</label>

                                <div class="input-group col-sm-10">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') ? old('email') : $setting->email }}" required>
                                </div>
                                <p><br />The E-Mail Address is used for receiving <b>Mail Notifications</b> and for the
                                    <b>Contact Button (Warning Pages)</b>.</p>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="control-label">Project SECURITY</label><br />
                                <input type="checkbox" name="project_security" value="1" class="psec-switch" {{ $setting->project_security == 1 ? 'checked' : '' }} />
                                <br /> With this option you can <strong>Enable</strong> or <strong>Disable</strong> the
                                whole script.<br />
                            </div>
                            <hr><br />
                            <div class="form-group">
                                <label class="control-label">Mail Notifications</label><br />
                                <input type="checkbox" name="mail_notifications" value="1" class="psec-switch" {{ $setting->mail_notifications == 1 ? 'checked' : '' }} />
                                </br> If this option is <strong>Enabled</strong> you will receive notifications on your
                                E-Mail Address.<br />
                            </div>
                            <hr><br />
                            <div class="form-group">
                                <label class="control-label">IP Detection</label><br />

                                <input type="checkbox" name="ip_detection" value="2" class="psec-switch" {{ $setting->ip_detection == 2 ? 'checked' : '' }} />
                                <br />(<b>Basic</b> / <b>Advanced</b>)<br /><br />

                                Basic IP Detection is used by default. <i>Faster performance, lower accuracy.</i><br />
                                If this option is <strong>Enabled</strong> will be used Advanced IP Detection. <i>Higher
                                    detection accuracy, slower performance.</i><br />
                            </div>
                        </div>
                        <div class="card-footer text-left row">
                            <div class="col-md-8">
                                <button class="btn btn-block btn-flat btn-primary" type="submit"><i
                                        class="fas fa-save"></i> Save</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-block btn-flat btn-default"><i
                                        class="fas fa-undo-alt"></i> Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var elems = Array.prototype.slice.call(document.querySelectorAll('.psec-switch'));

elems.forEach(function(html) {
  var switchery = new Switchery(html);
});
</script>

@endsection