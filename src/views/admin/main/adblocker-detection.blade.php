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
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-8">


                @if($ads->detection == 1)
                <div class="card card-solid card-success">
                @else
                <div class="card card-solid card-primary">
                @endif

                <div class="card-header">
                    <h3 class="card-title">AdBlocker Detection - Protection Module</h3>
                </div>
                <div class="card-body jumbotron">
                    @if($ads->detection == 1)
                        <h1 style="color: #47A447;"><i class="fas fa-check-circle"></i> Enabled</h1>
                        <p>Visitors with enabled <strong>AdBlockers</strong> are not allowed</p>
                    @else
                        <h1 style="color: #007bff;"><i class="fas fa-times-circle"></i> Disabled</h1>
                        <p>Visitors with enabled <strong>AdBlockers</strong> are allowed</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> What is AdBlocker</h3>
                </div>
                <div class="card-body">
                    <strong>AdBlocker</strong> is a piece of software that is designed to prevent advertisements from
                    appearing on a web page.
                </div>
            </div>

        </div>

        <div class="col-md-4">

            <form class="form-horizontal form-bordered" action="{{ route('ps.admin.adblocker-detection') }}" method="post">
                @csrf
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-cogs"></i> Settings</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                                <li class="list-group-item">
                                    <p>Detection</p>
                                    <input type="checkbox" name="detection" class="psec-switch" {{ $ads->detection == 1 ? 'checked' : '' }}/><br />
                                    <span class="text-muted">If this protection module is enabled all threats of this
                                        type will be blocked</span>
                                </li>
                                <li class="list-group-item">
                                    <p>Redirect URL</p>
                                    <input name="redirect" class="form-control" id="abredirect" type="text" value="{{ $ads->redirect }}" required>
                                </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-flat btn-block btn-primary" type="submit"><i
                                class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
</div>
<!--===================================================-->
<!--End page content-->

<script>
var elems = Array.prototype.slice.call(document.querySelectorAll('.psec-switch'));

elems.forEach(function(html) {
    var switchery = new Switchery(html);
});
</script>

@endsection