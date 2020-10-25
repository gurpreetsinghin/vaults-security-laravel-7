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
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">IP Range Bans</h3>
                    </div>
                    <div class="card-body">

                        <center><a href="{{ route('ps.admin.iprange-ban.delete-all') }}" class="btn btn-flat btn-danger" title="Delete all IP Bans"><i
                                    class="fas fa-trash"></i> Delete All</a></center>

                        <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> IP Range</th>
                                    <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bans as $ban)
                                <tr>
                                    <td>{{ $ban->ip_range }}</td>
                                    <td>
                                        <a href="{{ route('ps.admin.iprange-ban.edit', $ban->id) }}"
                                            class="btn btn-flat btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="{{ route('ps.admin.iprange-ban.delete', $ban->id) }}"
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
                        <h3 class="card-title">Ban IP Range</h3>
                    </div>
                    <form class="form-horizontal" action="{{ route('ps.admin.iprange-ban.add') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label">IP Range: </label>
                                <input name="ip_range" class="form-control" type="text" placeholder="Format: 12.34.56"
                                    pattern="[0-9]*\.?[0-9]*\.?[0-9]*" maxlength="11" value="" required>
                                @if ($errors->has('ip_range'))
			                        <label class="error">{{ $errors->first('ip_range') }}</label>
			                    @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-flat btn-danger" type="submit">Ban</button>
                            <button type="reset" class="btn btn-flat btn-default">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection