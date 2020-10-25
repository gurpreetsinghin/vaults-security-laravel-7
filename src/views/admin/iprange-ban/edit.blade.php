@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-grip-horizontal"></i> IP Range Bans</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">IP Range Bans</li>
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

            <div class="col-md-9">
                <form class="form-horizontal" action="{{ route('ps.admin.iprange-ban.edit', $ban->id) }}" method="post">
                    @csrf
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Edit - IP Range Ban #{{ $ban->id }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label">IP Range: </label>
                                <input name="ip_range" class="form-control" type="text" value="{{ $ban->ip_range }}" required>
                                @if ($errors->has('ip_range'))
			                        <label class="error">{{ $errors->first('ip_range') }}</label>
			                    @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-flat btn-success" type="submit">Save</button>
                            <button type="reset" class="btn btn-flat btn-default">Reset</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection