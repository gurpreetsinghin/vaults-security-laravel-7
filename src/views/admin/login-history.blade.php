@extends('project-security::layouts.admin')
@section('content')


<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-flag"></i> Login History</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Login History</li>
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
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Login History</h3>
                    </div>
                    <div class="card-body">
                        <table id="dt-basic" class="table table-striped table-bordered table-hover" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                <th><i class="fas fa-user"></i> Username</th>
                                <th><i class="fas fa-address-card"></i> IP Address</th>
                                <th><i class="far fa-calendar-alt"></i> Date & Time</th>
                                <th><i class="fas fa-info-circle"></i> Login Status</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logins as $login)
                                <tr>
                                    <td>{{ $login->username }}</td>
                                    <td>{{ $login->ip }}</td>
                                    <td>{{ $login->date }} at {{ $login->time }}</td>
                                    <td>
                                    @if($login->successful == 0)
                                    <span class="badge badge-danger">Failed</span>
                                    @else
                                    <span class="badge badge-success">Successful</span>
                                    @endif
                                    </td>
                                    <td>
                                    <a href="{{ route('ps.admin.ip-lookup', ['ip' => $login->ip]) }}" class="btn btn-flat btn-flat btn-primary"><i class="fas fa-search"></i> IP Lookup</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection