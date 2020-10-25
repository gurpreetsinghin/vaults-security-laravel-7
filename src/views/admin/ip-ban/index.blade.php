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
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">IP Bans</h3>
                    </div>
                    <div class="card-body">

                        <center><a href="{{ route('ps.admin.ip-ban.delete-all') }}" class="btn btn-flat btn-danger" title="Delete all IP Bans"><i
                                    class="fas fa-trash"></i> Delete All</a></center>

                        <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> IP Address</th>
                                    <th><i class="fas fa-calendar"></i> Banned On</th>
                                    <th><i class="fas fa-share"></i> Redirect</th>
                                    <th><i class="fas fa-magic"></i> Autobanned</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bans as $ban)
                                <tr>
                                    <td>{{ $ban->ip }}</td>
                                    <td>{{ $ban->date }}</td>
                                    <td>{{ $ban->redirect == 1 ? 'Yes' : 'No' }}</td>
                                    <td>{{ $ban->autoban == 1 ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.ip-ban.edit', $ban->id) }}"
                                            class="btn btn-flat btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="{{ route('ps.admin.ip-ban.delete', $ban->id) }}"
                                            class="btn btn-flat btn-success"><i class="fas fa-trash"></i> Unban</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Ban IP Address</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route('ps.admin.ip-ban.add') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">IP Address: </label>
                                <input name="ip" class="form-control" type="text" value="{{ old('ip') }}" required>
                                @if ($errors->has('ip'))
			                        <label class="error">{{ $errors->first('ip') }}</label>
			                    @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Reason: </label>
                                <input name="reason" class="form-control" type="text" value="{{ old('reason') }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Redirecting to page / site: </label>
                                <select name="redirect" class="form-control" required>
                                    <option value="0" selected>No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Redirect URL: </label>
                                <input name="url" class="form-control" type="url" value="{{ old('url') }}">
                            </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-flat btn-danger" type="submit">Ban</button>
                        <button type="reset" class="btn btn-flat btn-default">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection