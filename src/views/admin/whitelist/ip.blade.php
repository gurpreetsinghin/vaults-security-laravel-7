@extends('project-security::layouts.admin')
@section('content')


<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-flag"></i> IP Whitelist</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">IP Whitelist</li>
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
                        <h3 class="card-title">IP Whitelist</h3>
                    </div>
                    <div class="card-body">
                        <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> IP Address</th>
                                    <th><i class="fas fa-clipboard"></i> Notes</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ips as $ip)
                                <tr>
                                    <td>{{ $ip->ip }}</td>
                                    <td>{{ $ip->notes }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.whitelist.ip-edit', $ip->id) }}" class="btn btn-flat btn-flat btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="{{ route('ps.admin.whitelist.ip-delete', $ip->id) }}" class="btn btn-flat btn-flat btn-danger"><i class="fas fa-trash"></i> Delete</a>
											</td>
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
                        <h3 class="card-title">Add IP Address</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route('ps.admin.whitelist.ip-add') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">IP Address: </label>
                                <input type="text" name="ip" class="form-control" value="{{ old('ip') }}" required>
                                @if ($errors->has('ip'))
			                        <label class="error">{{ $errors->first('ip') }}</label>
			                    @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label">Notes: </label>
                                <textarea rows="5" name="notes" class="form-control"
                                    placeholder="Additional (descriptive) information can be added here">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-block btn-flat btn-primary" type="submit"><i
                                    class="fas fa-plus-square"></i> Add</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection