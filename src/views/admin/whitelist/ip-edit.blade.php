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
                        <form class="form-horizontal" action="{{ route('ps.admin.whitelist.ip-edit', $ip->id) }}" method="post">
                            @csrf
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">Edit IP Address</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="control-label">IP Address: </label>
                                        <input type="text" name="ip" class="form-control" value="{{ $ip->ip }}" required>
                                        @if ($errors->has('ip'))
                                            <label class="error">{{ $errors->first('ip') }}</label>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Notes: </label>
                                        <textarea rows="4" name="notes" class="form-control"
                                            placeholder="Additional (descriptive) information can be added here">{{ $ip->notes }}</textarea>
                                    </div>
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
    </div>
</div>

@endsection