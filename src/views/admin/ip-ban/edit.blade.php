@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user"></i> IP Bans</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">IP Bans</li>
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
                <form class="form-horizontal" action="{{ route('ps.admin.ip-ban.edit', $ban->id) }}" method="post">
                    @csrf
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Edit - IP Address Ban #{{ $ban->id }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label">IP Address: </label>
                                <input name="ip" class="form-control" type="text" value="{{ $ban->ip }}" required>
                                @if ($errors->has('ip'))
			                        <label class="error">{{ $errors->first('ip') }}</label>
			                    @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Reason: </label>
                                <input name="reason" class="form-control" type="text" value="{{ $ban->reason }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Redirecting to page / site: </label>
                                <select name="redirect" class="form-control" required>
                                    <option value="0" {{ $ban->redirect == 0 ? 'Selected' : '' }}>No</option>
                                    <option value="1" {{ $ban->redirect == 1 ? 'Selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Redirect URL: </label>
                                <input name="url" class="form-control" type="url" value="{{ $ban->url }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Banned On: </label>
                                <input name="date" class="form-control" type="text" value="{{ $ban->date }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Banned At: </label>
                                <input name="time" class="form-control" type="text" value="{{ $ban->time }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label">AutoBanned: </label>
                                <input name="autoban" class="form-control" type="text" value="{{ $ban->autoban == 1 ? 'Yes' : 'No' }}" readonly>
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