@extends('project-security::layouts.admin')
@section('content')


<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-flag"></i> File Whitelist</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">File Whitelist</li>
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
                        <h3 class="card-title">File Whitelist</h3>
                    </div>
                    <div class="card-body">
                        <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> File Address</th>
                                    <th><i class="fas fa-clipboard"></i> Notes</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($files as $file)
                                <tr>
                                    <td>{{ $file->path }}</td>
                                    <td>{{ $file->notes }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.whitelist.file-edit', $file->id) }}" class="btn btn-flat btn-flat btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="{{ route('ps.admin.whitelist.file-delete', $file->id) }}" class="btn btn-flat btn-flat btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
                        <h3 class="card-title">Add File</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route('ps.admin.whitelist.file-add') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">File's Path: </label>
                                <input type="text" name="path" class="form-control" value="{{ old('path') }}" required>
                                @if ($errors->has('path'))
			                        <label class="error">{{ $errors->first('path') }}</label>
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