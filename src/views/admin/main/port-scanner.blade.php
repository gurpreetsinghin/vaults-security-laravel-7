@extends('project-security::layouts.admin')
@section('content')

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-search"></i> Port Scanner</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ps.admin.dashboard') }}"><i class="fas fa-home"></i> Admin Panel</a></li>
                    <li class="breadcrumb-item active">Port Scanner</li>
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
                        <h3 class="card-title"><i class="fas fa-th-list"></i> Scan results for
                            <b>{{ $_SERVER['SERVER_NAME'] }}</b>
                        </h3>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-dot"></i> Port</th>
                                        <th><i class="fas fa-cogs"></i> Service</th>
                                        <th><i class="fas fa-info-circle"></i> Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $port => $val)
                                    @php $prot = getservbyport($port, "tcp"); @endphp
                                    <tr>
                                        <td>{{ $port }}</td>
                                        <td>{{ $prot }}</td>
                                        @if ($val)
                                        <td><a href="http://{{ $_SERVER['SERVER_NAME'] }}:{{ $port }}" target="_blank"
                                                class="badge badge-danger" style="font-size: 13px;"><i
                                                    class="fas fa-unlock"></i> Open</a></td>
                                        @else
                                        <td>
                                            <font class="badge badge-success" style="font-size: 13px;"><i
                                                    class="fas fa-lock"></i> Closed</font>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> What is Port Scanning</h3>
                    </div>
                    <div class="card-body">
                        Port Scanning is the name for the technique used to identify open ports and services available
                        on a network host. Port Scanning is used to determine which ports are open and vulnerable to
                        attacks.
                        <br /><br />
                        Port Scanning is a slow proccess and can take a while.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection