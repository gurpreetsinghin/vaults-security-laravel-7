@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user"></i> Account</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Account</li>
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
                <form class="form-horizontal" action="{{ route('ps.admin.account') }}" method="post">
                    @csrf
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Account</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label"><i class="fas fa-user"></i> Username: </label>
                                <input type="text" name="username" class="form-control" value="{{ $setting->username }}" required>
                                @if ($errors->has('username'))
			                        <label class="error">{{ $errors->first('username') }}</label>
			                    @endif
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="control-label"><i class="fas fa-key"></i> New Password: </label>
                                <input type="text" name="password" class="form-control">
                            </div>
                            <i>Fill this field only if you want to change the password.</i>
                        </div>
                        <div class="card-footer row">
                            <div class="col-md-8">
                                <button class="btn btn-block btn-flat btn-success" type="submit"><i
                                        class="fas fa-save"></i> Save</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-block btn-flat btn-default"><i
                                        class="fas fa-undo"></i> Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection